# AGENTS.md

Guidance for AI agents and developers working in this repository.

## What this is

**Bookshelfs** is a [Nextcloud](https://nextcloud.com) app (v0.1.1) that lets users arrange e-books on a virtual bookshelf: order books dynamically, style them (colour/pattern/height), and link them to files stored in Nextcloud.

- Author: Kars van Velzen (kars@octubre.be)
- License: AGPL-3.0-or-later
- Repo: https://github.com/JeCheeseSmith/bookshelfs
- **Status: very early development.** Expect bugs and possible data loss.

## Tech stack

| Layer | Tech |
|---|---|
| Backend | PHP 8.2, Nextcloud App Framework (OCSController, QBMapper, migrations) |
| Frontend | Vue 3, TypeScript, Vite, `@nextcloud/vue` v9, `vuedraggable` |
| Tooling | Composer, npm (Node ^24.13, npm ^11.6), PHPUnit 10.5, Psalm 5, php-cs-fixer, ESLint, stylelint |
| Target | Nextcloud 30–34 (see `appinfo/info.xml`) |

## Project layout

```
appinfo/info.xml          # App metadata, version, routes/navigation, NC version range
lib/
  AppInfo/Application.php # App bootstrap (APP_ID = 'bookshelfs'), currently empty register/boot
  Controller/             # PageController (frontend page) + BooksController (REST/OCS API)
  Db/                     # Book entity + BookMapper (database access)
  Migration/              # DB schema migration(s)
src/                      # Vue frontend (main.ts, App.vue, components/, models/, utils.ts)
templates/index.php       # Server-rendered shell that loads the JS bundle
tests/                    # PHPUnit config + integration tests
docs/                     # dev.md (dev setup), demo.gif, bright.png
```

## The data model

Books live in a single per-user DB table `bookshelfs`. Fields (note the **British spelling `colour`** — keep it consistent everywhere):

| Field | Type | Meaning |
|---|---|---|
| `id` | bigint (PK, auto) | |
| `userid` | string(64) | owning Nextcloud user |
| `title` | string(36) | |
| `author` | string(4) | **only 4 chars** (schema quirk — don't silently change) |
| `position` | bigint | shelf order (array index) |
| `url` | text | **file ID of the cover image** (not a URL) |
| `file` | bigint | file ID of the e-book file |
| `colour` | string(64) | CSS colour name |
| `pattern` | smallint | pattern index (0–3) |
| `height` | smallint | rendered height (px) |

Mirror of this model:
- PHP: `lib/Db/Book.php` (Nextcloud `Entity`, `JsonSerializable`)
- TS: `src/models/Book.ts` (`Book` interface + `constructBook`)
- Schema: `lib/Migration/Version0000Date20260114110456.php`

## API

REST endpoints via `BooksController` (`lib/Controller/BooksController.php`), OCS format, `@NoAdminRequired`, OpenAPI ignored:

- `GET /apps/bookshelfs/api/v1/books` — list current user's books
- `POST /apps/bookshelfs/api/v1/books` — create book
- `POST /apps/bookshelfs/api/v1/books/from-file` — create book from an e-book file (`file` = file ID; extracts title/author/cover automatically, random style unless `colour`/`pattern`/`height` given)
- `PUT /apps/bookshelfs/api/v1/books/{id}` — update (all fields optional, partial update)
- `DELETE /apps/bookshelfs/api/v1/books/{id}` — delete

Book auto-setup lives in `lib/Service/EbookFileService.php`: EPUBs are parsed with PHP `ZipArchive` + `DOMDocument` (title/author from OPF, cover from `<meta name="cover">`), PDFs get title/author from the info dict and their cover from Nextcloud's preview (first page). Extracted covers are stored in `/Bookshelfs/covers/` in the user's files; authors are truncated to 4 chars (schema quirk).

The frontend page (`PageController::index`, route `bookshelfs.page.index`) injects the user's books as initial state under key `bookshelfs-initial-state` (`loadState('bookshelfs', 'bookshelfs-initial-state')` in `App.vue`).

Cover images are rendered via Nextcloud preview endpoint: `/index.php/core/preview?fileId={book.url}&x=190&y=280` (`src/utils.ts:getPath`).

## Commands

```bash
# Backend
composer install            # install deps (also installs vendor-bin tools)
composer lint               # php -l over all PHP files
composer cs:check           # php-cs-fixer dry-run (style check)
composer cs:fix             # php-cs-fixer fix
composer psalm              # static analysis (psalm --no-cache)
composer test:unit          # PHPUnit (requires a running Nextcloud server, see below)

# Frontend
npm ci                      # install deps
npm run build               # production build via Vite
npm run dev                 # dev server
npm run watch               # watch + rebuild
npm run lint                # ESLint (eslint.config.js)
npm run stylelint           # stylelint for src/**/*.{vue,scss,css}

# Release
make build_release          # build + sign a release tarball (needs certs)
```

## Testing

`composer test:unit` / the CI `php` job run **DB integration tests**, not pure unit tests. They require a fully installed Nextcloud server (tests bootstrap loads `tests/bootstrap.php` from a sibling Nextcloud checkout, i.e. `tests/../../../tests/bootstrap.php`), so they must run inside a Nextcloud instance, not standalone.

- Tests: `tests/unit/Mapper/BooksMapperTest.php` (marked `@group DB`; needs the app enabled and hits the real DB; cleans up after itself).
- Run inside the docker dev server (see below):
  ```
  docker exec -it -u www-data master-nextcloud-1 bash -c \
    "cd apps-extra/bookshelfs && ./vendor/phpunit/phpunit/phpunit -c tests/phpunit.xml"
  ```

## Local dev setup

Quick start (from `docs/dev.md`):

```bash
docker run --rm -p 8080:80 -v ./:/var/www/html/apps-extra/bookshelfs ghcr.io/juliusknorr/nextcloud-dev-php82:latest
npm run watch   # rebuild frontend while developing
```

## CI (`.github/workflows`)

- `tests.yml` — on every PR:
  - **code-lint** job: PHP lint, php-cs-fixer, Psalm, stylelint. **Note: the ESLint step is currently commented out**, so PHP checks are the enforced gate for now.
  - **php** job: PHPUnit matrix across sqlite/mysql/pgsql × server stable30–33/master.
- `release.yml`, `openapi.yml.old` (outdated — ignore), dependency-update workflows for `nextcloud/ocp` (`nextcloud/ocp` is pinned to `dev-stable29` in composer.json).

## Conventions to follow

- **SPDX headers**: every file starts with an SPDX copyright/license block, e.g.
  ```
  //
  //  - SPDX-FileCopyrightText: 2026 Kars van Velzen
  //  - SPDX-FileCopyrightText: 2025 Nextcloud GmbH and Nextcloud contributors
  //  - SPDX-License-Identifier: AGPL-3.0-or-later
  //
  ```
  New files should include one; don't strip existing ones.
- **`declare(strict_types=1)`** in every PHP file.
- Use the Nextcloud App Framework idioms already in the codebase:
  - `OCP\AppFramework\Db\Entity` for models; `QBMapper` + query builder for data access (never raw SQL strings).
  - `#[ApiRoute]`/`#[FrontpageRoute]` attributes (modern style), `@NoAdminRequired` on user-facing endpoints.
  - Validate ownership by passing `$this->userId` into mapper methods.
- **Naming**: British spelling `colour` (DB column, entity, API, TS model — all match). `userid` (no camelCase) for the user column.
- Frontend uses `@nextcloud/vue` components and `@nextcloud/*` helpers (`axios`, `initial-state`, `router`, `dialogs`, `l10n`). Keep using them rather than adding new libs.
- Version lives in **two** places — bump both together: `appinfo/info.xml` (`<version>`) and `package.json` (`"version"`).
- Node/npm versions are pinned: see `.nvmrc` and `package.json` `engines`.

## Gotchas / things that look wrong but are intentional

- `author` DB column is length 4 (schema quirk; don't widen it without a migration + author sign-off).
- `Book::jsonSerialize()` includes `userid` even though the API contract docs omit it — fine, but the TS `Book` model does not include `userid`.
- `url` is stored as a *file ID* for the cover, not a URL string.
- ESLint is not enforced in CI yet; run `npm run lint` locally to be safe.
