# CI/CD — iHowz theme

One workflow: `deploy.yml` (`CI/CD`). It mirrors the CaseMate methodology and
pairs with `JonHubbard1/ihowz`'s workflow, which deploys the plugin half of the
site.

## The model

| Event | What happens |
| --- | --- |
| Pull request → `main` | `Tests` runs (PHP syntax sweep + zip build). Nothing deploys. `Tests` is a **required** status check — a red Tests blocks merge. CodeRabbit reviews independently. |
| Merge to `main` | `Tests` → `Deploy → Staging` (https://ihowz.makeapp.uk on VM1100). |
| **Release published** | `Tests` → `Deploy → Production` (https://ihowz.uk on Pinot) + the built zip is attached to the release. |
| `workflow_dispatch` | Emergency override only. Deploys the chosen ref to the chosen environment; `skip_tests` bypasses the gate (do not use it casually). |

**Production deploys only when a human publishes a GitHub release.** The
release tag must equal the `Version:` header in `style.css` (leading `v`
ignored — tag `v1.8.7` ↔ `Version: 1.8.7`), or the deploy fails before
touching the server.

This replaces the old `release.yml`, which created a release automatically on
every push to `main` — under this model that would have made every merge an
automatic production deploy.

## Why artifacts instead of `git pull`

The WordPress install directories on the servers are not git checkouts, so CI
cannot pull a revision onto them. Instead:

1. The `Tests` job (hosted `ubuntu-latest`) runs `php -l` over every tracked
   PHP file, then builds `dist/ihowz-theme-<version>.zip` from tracked files
   (root folder `ihowz/`, ready for `wp-content/themes/`), excluding
   development infrastructure (`.github/`, `.ploi/`, `.claude/`,
   `docs/screenshots/`, the session-management reports, stray
   logs/backups). Uploaded as the `theme-zip` artifact.
2. Deploy jobs (self-hosted `[self-hosted, linux, proxmox]`) download the zip
   and `scp` it plus `.ploi/deploy-wp.sh` into
   `/home/ploi/.ihowz-deploy/<run-id>-<attempt>/` on the target.
3. `deploy-wp.sh theme` verifies the zip's `Version:` header *before*
   swapping, moves the old `wp-content/themes/ihowz` to
   `/home/ploi/<site-root>/.ihowz-backups/theme/<ts>` (kept outside the web
   root and outside `wp-content`, last 5), installs the new directory, and
   writes `.ihowz-pipeline/theme.marker`. **CI never changes which theme is
   active** — that is an editorial choice; it only replaces the files of the
   `ihowz` theme directory in place.
4. `Verify deployed code` asserts the marker's `sha=` equals the commit that
   was deployed; `Smoke test` curls `/` and `/wp-login.php` **from the target
   itself** (the public domain hairpins through Cloudflare) and requires
   2xx/3xx.

There is no PHP unit-test harness for the theme (no composer.json, no
phpunit.xml) — the gate is the syntax sweep plus a successful artifact build;
real-world coverage is the staging smoke. If a test suite is ever added, wire
it into the `Tests` job before the zip build.

## Releasing a new version

1. Bump `Version:` in `style.css` on `main` (via PR).
2. When it should go live: draft a release tagged `v<version>` matching the
   header, publish it. CI does the rest — including attaching
   `ihowz-theme-<version>.zip` to the release.

## GitHub configuration

Same recipe as the plugin repo (see its workflows README): branch protection
on `main` requiring `Tests`; environments `staging` / `production` with vars
`STAGING_URL` / `PRODUCTION_URL` and secrets `STAGING_HOST/PATH/SSH_KEY`,
`PRODUCTION_HOST/PATH/SSH_KEY`; one ED25519 deploy keypair **per repo per
environment** (`deploy:ihowz-theme:staging` / `:production` comments in the
ploi user's `authorized_keys`); delete branch on merge.

## Superseded

`release.yml` (auto-release on every push) and `deploy-release.yml` (dead
`pinot-ihowz-theme` runner label, root SSH to a retired Tailscale address,
Coolify-path server script) are both deleted.
