# CRM contract regeneration with the existing PHP workflow

The PHP SDK uses the Speakeasy workflow in `.speakeasy/workflow.yaml`. A contract
update replaces `spec/openapi.json` with the reviewed export and regenerates the
files tracked by `.speakeasy/gen.lock`. Keep `src/Custom/`, handwritten tests and
examples intact. Passing adapter tests against a fake transport does not prove
that the generated SDK covers a newer API export.

## Prerequisites

Use the official [Speakeasy 1.800.0 release](https://github.com/speakeasy-api/speakeasy/releases/tag/v1.800.0)
and verify its archive against the release's `checksums.txt` before extracting
the binary. This version matches the existing generator lock. Install the binary
in an isolated tools directory; a global CLI update is unnecessary.

A genuine Speakeasy workspace credential is required. The official CLI's
`auth status` command reports authentication separately from its exit code:
exit zero can also mean `Not authenticated`. The `run` command authenticates
before processing the workflow; `--auto-yes` does not supply authentication.
When authentication is unavailable, record generation as pending. Do not use a
placeholder key or treat an imported spec as newly generated SDK code.

The repository's `.speakeasy/workflow.local.yaml` pins CLI 1.800.0 and disables
code samples. Keep that override for a local generation without publication.
`codeSamples.blocking: false` in the base workflow only tolerates an upload
failure; it does not disable the upload.

## Import the verified current export

Run from the repository root with the export path and its reviewed SHA-256:

```bash
: "${FACTUAREA_SPEC_SOURCE:?Set the current reviewed export path}"
: "${FACTUAREA_SPEC_SHA256:?Set the reviewed SHA-256}"

if ! printf '%s  %s\n' "$FACTUAREA_SPEC_SHA256" "$FACTUAREA_SPEC_SOURCE" | sha256sum --check --status; then
  printf '%s\n' 'The export checksum does not match.' >&2
  exit 1
fi
cp -- "$FACTUAREA_SPEC_SOURCE" spec/openapi.json
```

Stop if checksum verification fails. Do not reuse an older export because its
generated classes already exist. Preserve all three existing overlays: retry
status-code casing, product-options namespace and pagination removal.

## Generate without registry publication

After authentication is available, point `FACTUAREA_SPEAKEASY_CLI` at the verified
binary and run:

```bash
: "${FACTUAREA_SPEAKEASY_CLI:?Set the verified Speakeasy 1.800.0 binary path}"

"$FACTUAREA_SPEAKEASY_CLI" run \
  --target factuarea \
  --auto-yes \
  --skip-upload-spec \
  --skip-testing \
  --skip-compile \
  --skip-versioning \
  --minimal \
  --output console
```

The explicit flags skip spec publication and automatic version changes; the
local override also disables code-sample publication. The workflow still uses
the authenticated Speakeasy generator service. Local output does not imply
offline generation.

## Review and verify the result

Inspect the resulting file list before accepting generated changes. Verify that
the imported spec, generated classes, generated documentation and generator
lock describe the same export. Preserve handwritten Source and existing
dependency installations; do not update Composer dependencies as a side effect
of a contract-only change.

The command deliberately skips compilation and tests. Run the checks required
by the actual generated changes and exercise newly exposed routes through the
existing SDK configuration and transport. Report generated coverage, installed
package imports and native backend execution separately. Tests with fake HTTP
responses establish their tested SDK behavior; they do not establish tenant,
ACL or backend execution in a live application.

Primary CLI behavior is defined by the pinned
[authentication commands](https://raw.githubusercontent.com/speakeasy-api/speakeasy/v1.800.0/cmd/auth.go),
[command authentication wrapper](https://raw.githubusercontent.com/speakeasy-api/speakeasy/v1.800.0/internal/model/command.go)
and [generation target](https://raw.githubusercontent.com/speakeasy-api/speakeasy/v1.800.0/internal/run/target.go).
