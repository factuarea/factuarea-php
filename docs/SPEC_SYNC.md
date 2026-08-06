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
