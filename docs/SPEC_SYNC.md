# Cross-repo spec synchronisation

The published Factuarea OpenAPI document is the single source of truth for this
SDK (design D10). This repo holds a **pinned copy** in `spec/openapi.json`; the
SDK is generated from it so the repo is reproducible on its own. This document
describes the two sides of keeping that copy in sync.

---

## Receiver side (implemented here)

[`.github/workflows/spec-sync.yml`](../.github/workflows/spec-sync.yml) is the
receiver. It runs on three triggers:

- **`repository_dispatch`** with `event_type: spec-updated` — the push signal
  from the `factuarea` repo (emitter, below).
- **`workflow_dispatch`** — manual run from the Actions tab.
- **`schedule`** — a daily cron fallback, so a missed dispatch is still caught.
  **Currently PAUSED**, together with the emitter in the `factuarea` repo; the
  reactivation condition is written in the workflow itself.

What it does:

1. Downloads the published spec from
   `https://docs.factuarea.com/api/openapi` (task 0.4).
2. **Compares canonically.** The published spec is minified and the pinned copy
   is pretty-printed, so a raw byte diff is meaningless. The workflow normalises
   both with `jq -S .` (recursively sorted keys) and compares the canonical
   serialisations. Only a real structural change triggers regeneration.
3. If they differ: re-pins the spec (pretty-printed), regenerates with the
   Speakeasy CLI (the retry-casing overlay in `.speakeasy/workflow.yaml` is
   applied automatically), installs dependencies, and runs PHPUnit on PHP 8.2.
4. Opens a **pull request** with the diff. It **never pushes to `main`** — every
   spec change is reviewed, the version bumped, and the changelog updated by hand.

### Required secret

| Secret              | Why                                                  |
| ------------------- | --------------------------------------------------- |
| `SPEAKEASY_API_KEY` | Authenticates the Speakeasy CLI to regenerate in CI. |

It is already set on this repo (workspace `factuarea/factuarea`, key
`github-actions-factuarea-php`). The workflow fails with a clear message if it is
missing rather than producing a partial generation.

---

## Emitter side (implemented in the private `factuarea` repo)

The emitter is the `SDK Spec Dispatch` workflow
(`.github/workflows/sdk-spec-dispatch.yml`) in the private `factuarea` repo. It
fires the `repository_dispatch` into this repo and into `factuarea-node`:

```sh
# GH_TOKEN is the SDK_DISPATCH_TOKEN secret of the private repo.
for repo in factuarea/factuarea-node factuarea/factuarea-php; do
  gh api "repos/${repo}/dispatches" -f event_type=spec-updated
done
```

Its triggers are:

- **`workflow_dispatch`** — the reference trigger, run by hand right after the
  documentation portal is republished. That is the only moment the published
  spec is known to have changed.
- **`push` of a `v*` tag** — the release event that exists in the private repo,
  as a best-effort automatic nudge.

It deliberately does **not** fire on pushes to `develop`. The receiver reads the
*published* spec, not the spec on a branch, so a `develop` push would only
produce a run that finds nothing to sync. The published document is served by a
separate repo (`factuarea-docs`) that has no hook back into `factuarea`, so
there is no unambiguous "the published spec just changed" event to hang the
emitter on — which is why the manual trigger is the primary one. A tag push may
land before the portal is republished; the run then reports that there is
nothing to sync, and the daily schedule picks the change up within 24h.

Earlier revisions of this document showed the emitter detecting the change by
diffing `backend/public/docs/openapi-public.json` between commits. That file is
generated in CI and is **not** versioned in `factuarea`, so no such diff exists;
the deployed emitter does not attempt it.

### Why the emitter sends no `spec_url`

The dispatch carries **no `client_payload`**, and must not: `SPEC_URL` is fixed
in this receiver so that a dispatch cannot point CI at a foreign spec, which
would be downloaded, regenerated into code and executed on the runner. A
`repository_dispatch` is only a "go regenerate" signal, never a "from where".
Sending a value the receiver ignores on purpose invites someone to "fix" the
inconsistency the wrong way round — by making the receiver honour it.

### Secret to create in the `factuarea` repo

| Secret               | Scope / type                                                                 |
| -------------------- | --------------------------------------------------------------------------- |
| `SDK_DISPATCH_TOKEN` | A fine-grained PAT (or GitHub App installation token) scoped to `factuarea/factuarea-php` and `factuarea/factuarea-node`, with **Contents: Read and write** (plus the mandatory **Metadata: Read-only**). There is no permission literally named `repository_dispatch`: `POST /repos/{owner}/{repo}/dispatches` is gated by *Contents* write, so read-only is not enough. |

The default `GITHUB_TOKEN` cannot dispatch to **other** repositories, which is
why a dedicated token is required. Keep it in the `factuarea` repo's Actions
secrets only — never in these SDK repos.

If the secret is missing, the emitter **fails red** instead of skipping the
dispatch quietly. An emitter that silently does nothing is how this SDK went two
months without a spec update.

---

## Auditing the sync state

**When was the spec last checked?** The date of the most recent
[`Spec sync` run](https://github.com/factuarea/factuarea-php/actions/workflows/spec-sync.yml),
which the badge at the top of the [README](../README.md) links to. That date is
*not* the date of the last commit: a run that finds the spec unchanged is the
normal outcome and leaves no commit behind, so from the commit log a repo that
is checked daily and one nobody watches look the same.

**Is anything still watching?** PHPUnit asserts, in
[`Tests/Workflows/SpecSyncTriggersTest.php`](../Tests/Workflows/SpecSyncTriggersTest.php),
that at least one unattended trigger — `repository_dispatch` or `schedule` — is
live in `spec-sync.yml`. `workflow_dispatch` does not count: it fires when
someone remembers, and that is the assumption that failed. The assertion has no
allowlist, so switching the last one off turns CI red instead of going unnoticed.

**How big is the drift?** A sync PR states it in its title and body
(`+N/-M ops`), and the run's job summary lists every operation being withdrawn
by name — which is what the changelog has to pair with a replacement.

### Pausing a trigger

Pausing a trigger is allowed. Pausing it silently is not: the pause carries the
condition that brings it back, written so whoever reads the file next can check
it without knowing why it was paused.

```yaml
# PAUSED schedule since 2026-06-07; reactivate when: the published spec has at
# least as many paths as spec/openapi.json
# schedule:
#   - cron: "17 6 * * *"
```

The test rejects a disabled trigger with no such line, and rejects a vague one —
the date and the condition are both mandatory, so "paused for now, will re-enable
later" does not pass.

Reactivating **replaces** that note with the evidence the condition was met, and
the date it was met. The same test rejects an active trigger still carrying its
pause note, so the substitution cannot be skipped:

```yaml
# `schedule` restored 2026-08-06: published spec 345 paths vs 192 pinned, which
# satisfies the `docs == prod` condition it was paused under on 2026-06-07.
```

A pause that outlives its own condition is not caught by re-reading the comment;
nobody re-reads comments, which is how the 2026-06 pause survived two months
past the moment its condition inverted. It is caught by its effects: while one
trigger is live, the accumulated drift shows up as a sync PR whose title carries
its magnitude.
