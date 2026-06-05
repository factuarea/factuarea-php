# Releasing

`factuarea/factuarea-php` is published on **Packagist**, which versions a package
by its **git tags** — there is no artifact upload. A release is therefore an
annotated `v*` tag, and the rest is automated (design D6, task 5.2).

---

## How a release works

1. **Prepare** the release commit on `main`:
   - Bump `php.version` in `.speakeasy/gen.yaml`.
   - Add a `## [x.y.z] — YYYY-MM-DD` section to [`CHANGELOG.md`](../CHANGELOG.md).
   - If the default `Factuarea-Version` changed, update
     `FactuareaVersionHook::DEFAULT_VERSION` and [`docs/VERSIONING.md`](VERSIONING.md).
2. **Tag** the commit and push the tag:
   ```bash
   git tag -a v0.1.0 -m "v0.1.0"
   git push origin v0.1.0
   ```
3. The [release workflow](../.github/workflows/release.yml) then:
   - builds and runs the tests on PHP 8.2 / 8.3 / 8.4,
   - **guards** that the tag matches `php.version` in `.speakeasy/gen.yaml`
     (a mismatch fails the release),
   - publishes a **GitHub Release** with the changelog section for that tag
     (`0.x` tags are marked as pre-releases).
4. **Packagist updates automatically** from the GitHub service hook configured
   when the package is first submitted (see below). Within a few minutes the new
   tag appears on
   [packagist.org/packages/factuarea/factuarea-php](https://packagist.org/packages/factuarea/factuarea-php).

No Packagist API token is needed in CI: the GitHub ↔ Packagist auto-update is a
webhook on Packagist's side, not a push from GitHub Actions.

---

## First publication (one-time, manual — task 5.5)

Packagist requires a one-time **Submit** to create the package and claim the
`factuarea` vendor. This is a **web step the maintainer must do** (it cannot be
done from CI without the user's Packagist token):

1. Ensure the `v0.1.0` tag exists on `factuarea/factuarea-php` (created by P2b).
2. Log in to Packagist (account `Chelu97`, via GitHub).
3. Go to **https://packagist.org/packages/submit**.
4. Paste the repository URL: **`https://github.com/factuarea/factuarea-php`**.
5. Submit. Packagist reads `composer.json`, creates
   `factuarea/factuarea-php`, and claims the `factuarea` vendor automatically.
6. On the new package page, confirm **Auto-update** is enabled (Packagist sets up
   the GitHub hook automatically when you submit via a GitHub-linked account). If
   it is not, click **Settings → enable GitHub hook**, or use the manual
   **Update** button after each tag.

After this one-time submit, every future `v*` tag is picked up automatically — no
further manual steps.

---

## Verifying a release

After the GitHub Release exists and Packagist has updated, verify a clean install
(no host PHP/Composer needed — use Docker):

```bash
mkdir /tmp/factuarea-php-verify && cd /tmp/factuarea-php-verify
docker run --rm -v "$PWD":/app -w /app composer:2 \
  require factuarea/factuarea-php:^0.1
docker run --rm -v "$PWD":/app -w /app php:8.2-cli \
  php -r 'require "vendor/autoload.php"; var_dump(class_exists(\Factuarea\Sdk\Factuarea::class));'
# expect: bool(true)
```

If `composer require` reports the package is not found, Packagist has not picked
up the package/tag yet:

- Confirm the **GitHub auto-update hook** is active on the package page.
- If it is missing, click **Update** on
  https://packagist.org/packages/factuarea/factuarea-php, or (re)connect the hook
  in the package settings. The auto-update hook is the only thing that keeps
  Packagist in step with new tags; if it is off, every release needs a manual
  Update click.
