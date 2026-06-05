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

## Emitter side (NOT implemented — pending an org PAT)

The emitter lives in the **private `factuarea` repo** and fires the
`repository_dispatch` when the public spec changes on `develop`/release. It is
**not implemented yet** because it needs a Personal Access Token (or GitHub App
token) with `repo` scope on the `factuarea` org to dispatch into this repo — a
manual setup step the user must complete.

Until then, the **daily schedule** in the receiver keeps the SDK in sync within
24h of any spec change; the dispatch only makes it near-instant.

### Snippet to add to the `factuarea` repo CI

Add a step to the workflow that regenerates/exports `openapi-public.json` (after
it is committed/published), gated to only fire when the spec actually changed:

```yaml
# .github/workflows/<spec-export-or-deploy>.yml in the private `factuarea` repo.
# Fires after openapi-public.json is published, on develop / release only.
  notify-sdk-repos:
    name: Notify SDK repos of a spec change
    runs-on: ubuntu-latest
    # Only when the published spec actually changed in this push.
    steps:
      - uses: actions/checkout@v4
        with:
          fetch-depth: 2

      - name: Detect spec change
        id: spec
        run: |
          if git diff --quiet HEAD~1 HEAD -- backend/public/docs/openapi-public.json; then
            echo "changed=false" >> "$GITHUB_OUTPUT"
          else
            echo "changed=true" >> "$GITHUB_OUTPUT"
          fi

      - name: Dispatch spec-updated to the PHP SDK
        if: steps.spec.outputs.changed == 'true'
        env:
          GH_TOKEN: ${{ secrets.SDK_DISPATCH_TOKEN }}
        run: |
          gh api repos/factuarea/factuarea-php/dispatches \
            -f event_type=spec-updated

      - name: Dispatch spec-updated to the Node SDK
        if: steps.spec.outputs.changed == 'true'
        env:
          GH_TOKEN: ${{ secrets.SDK_DISPATCH_TOKEN }}
        run: |
          gh api repos/factuarea/factuarea-node/dispatches \
            -f event_type=spec-updated
```

### Secret to create in the `factuarea` repo

| Secret               | Scope / type                                                                 |
| -------------------- | --------------------------------------------------------------------------- |
| `SDK_DISPATCH_TOKEN` | A fine-grained PAT (or GitHub App installation token) with **Contents: read** and **`repository_dispatch` / Actions** write on `factuarea/factuarea-php` and `factuarea/factuarea-node`. |

The default `GITHUB_TOKEN` cannot dispatch to **other** repositories, which is
why a dedicated token is required. Keep it in the `factuarea` repo's Actions
secrets only — never in these SDK repos.
