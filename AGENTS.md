# AGENTS.md

Guidance for coding agents working in this repository.

## Project overview

TYPO3 extension providing tools for editorial style guides: content elements, static templates, predefined patterns and ViewHelpers.

- Composer package: `move-elevator/typo3-styleguide`
- Extension key: `typo3_styleguide`
- Namespace: `MoveElevator\Styleguide`
- PHP `~8.2 || ~8.3 || ~8.4 || ~8.5`, TYPO3 `^12.0 || ^13.0 || ^14.0`, `ext-dom`, `ext-intl`
- Optional: `blueways/bw-static-template` renders any Fluid template or partial as a content element

## Structure

- `Classes/`: PHP code (`Configuration.php` holds `EXT_KEY`, `EXT_NAME`, `PAGE_TYPE`), with `Middleware/`, `Mcp/`, `Preview/`, `UserFunc/` and `ViewHelpers/` (with a `Format/` sub-namespace)
- `Configuration/`: `TCA/` (content elements, page types and TypoScript templates are registered in `TCA/Overrides/`), `TsConfig/`, `TypoScript/`, `Services.yaml`, `RequestMiddlewares.php`, `Icons.php`
- `Resources/`: `Private/` (Fluid templates, language files, pattern templates under `Templates/Patterns/`) and `Public/`
- `Tests/Unit/`: PHPUnit unit tests
- `Tests/Acceptance/Fixtures/`: fixtures for the acceptance environment
- `Tests/CGL/`: separate Composer project holding all code quality tools and their configs
- `Documentation/`: generated ViewHelper docs and content element notes
- `.ddev/`: DDEV setup with custom commands (`cgl`, `install`, `all`, `typo3`, and one command per TYPO3 version)
- `ext_localconf.php`, `ext_tables.php`, `ext_tables.sql`, `ext_emconf.php`: classic extension registration files

## Development commands

All commands run inside DDEV.

```bash
ddev start
ddev composer install
ddev install all       # install every supported TYPO3 version into .Build/<version>/
ddev install 13        # install a single version
ddev launch            # open the overview page
ddev 12 typo3 cache:flush              # TYPO3 CLI in the v12 instance
ddev all typo3 database:updateschema   # run across all instances
```

Each TYPO3 version has its own docroot, database and hostname (`<version>.typo3-styleguide.ddev.site`).

Regenerate the ViewHelper documentation with `ddev composer doc:viewhelpers`. It scans `Classes/ViewHelpers` and writes to `Documentation/ViewHelpers/`.

## Testing

```bash
ddev composer test:unit    # phpunit -c phpunit.xml.dist, suite Tests/Unit
```

CI runs the CGL checks via the shared reusable workflow `cgl-test.yml`. The repository also has release, scorecard and security workflows, all thin wrappers around shared reusable workflows.

## Code style and static analysis

The tools live in `Tests/CGL/`. `ddev cgl <script>` (or `composer cgl <script>`) delegates to `composer -d Tests/CGL`.

```bash
ddev cgl lint                 # all linters
ddev cgl fix                  # auto-fix
ddev cgl sca                  # static analysis (PHPStan)
ddev cgl migration            # Rector
ddev cgl analyze              # composer-dependency-analyser
```

Individual scripts: `lint:composer`, `lint:editorconfig`, `lint:language`, `lint:php`, `fix:composer`, `fix:editorconfig`, `fix:php`, `sca:php`, `migration:rector`, `analyze:dependencies`.

- PHP CS Fixer via `konradmichalik/php-cs-fixer-preset`, file headers are generated from `composer.json` metadata
- PHPStan level 8 with strict rules, config in `Tests/CGL/phpstan.neon` and `phpstan-baseline.neon`
- Rector with `ssch/typo3-rector`, config in `Tests/CGL/rector.php`
- Every PHP file declares `declare(strict_types=1);`

## Git workflow

- Commit format: `<type>: <description>`
- Types: `feat`, `fix`, `refactor`, `docs`, `test`, `chore`, `perf`, `ci`
- No co-author trailers
- One commit per logical change
