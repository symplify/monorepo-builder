# Monorepo Builder

[![Downloads total](https://img.shields.io/packagist/dt/symplify/monorepo-builder.svg?style=flat-square)](https://packagist.org/packages/symplify/monorepo-builder)

A set of tools for managing PHP monorepos: merging `composer.json` files, validating package versions, releasing with automation, and more.

## Install

```bash
composer require monorepo-php/monorepo --dev
```

Requires PHP 8.2+. For PHP 8.1, use `symplify/monorepo-builder:^11.2` (no longer maintained).

## Quick Start

```bash
# 1. Scaffold a basic monorepo layout (one time)
vendor/bin/monorepo-builder init

# 2. Fold every package's composer.json into the root composer.json
vendor/bin/monorepo-builder merge

# 3. Cut a release when you're ready
vendor/bin/monorepo-builder release v1.0
```

All configuration goes in `monorepo-builder.php` at your project root. See [Configuration](#configuration) for the full list of options.

## Commands

### init

Generates a basic monorepo skeleton (a `packages/` directory and a starter `monorepo-builder.php`) so you can start adding packages immediately:

```bash
vendor/bin/monorepo-builder init
```

Run once at the start of a new monorepo. Existing files are not overwritten.

### merge

Merges all sections from package `composer.json` files into the root `composer.json`. For the reverse direction, see [`propagate`](#propagate).

```bash
vendor/bin/monorepo-builder merge
```

**Behavior:**

- All sections are merged, including standard (`require`, `autoload`, etc.) and custom ones (`scripts-aliases`, `abandoned`, etc.)
- If a package appears in both `require` and `require-dev`, the `require` entry takes priority
- The original key order of the root `composer.json` is preserved; new sections are appended at the end

To customize what gets merged (append / remove data, reorder sections, skip autoload merging, etc.) see [Customizing merge output](#customizing-merge-output).

### validate

Checks that all packages use the same version for shared dependencies:

```bash
vendor/bin/monorepo-builder validate
```

### bump-interdependency

Updates mutual dependencies between packages to a given version:

```bash
vendor/bin/monorepo-builder bump-interdependency "^4.0"
```

### propagate

Propagates versions from root `composer.json` back to each package's `composer.json` (the reverse of [`merge`](#merge)):

```bash
vendor/bin/monorepo-builder propagate
```

### package-alias

Updates the `branch-alias` in every package `composer.json` to match the current version:

```bash
vendor/bin/monorepo-builder package-alias
```

To customize the alias format string, see [Custom alias format](#custom-alias-format) under Configuration.

### localize-composer-paths

Sets mutual package paths to local packages for pre-split testing:

```bash
vendor/bin/monorepo-builder localize-composer-paths
```

### release

Automates the release process: bumping dependencies, tagging, pushing, and updating changelogs.

```bash
vendor/bin/monorepo-builder release v7.0
```

Preview what will happen without making changes:

```bash
vendor/bin/monorepo-builder release v7.0 --dry-run
```

Release by semver level (`patch`, `minor`, or `major`):

```bash
# current v0.7.1 → v0.7.2
vendor/bin/monorepo-builder release patch
```

The default pipeline runs `TagVersionReleaseWorker` followed by `PushTagReleaseWorker`. To customize the pipeline (add workers, reorder, disable defaults, enable LTS-aware tag resolution), see [Customizing the release pipeline](#customizing-the-release-pipeline).

## Configuration

All configuration lives in `monorepo-builder.php` at your project root. Every option below is set on the `MBConfig` instance passed into the configurator closure:

```php
use Symplify\MonorepoBuilder\Config\MBConfig;

return static function (MBConfig $mbConfig): void {
    // your configuration here
};
```

### Package discovery

By default, packages are discovered from `./packages`. To customize:

```php
return static function (MBConfig $mbConfig): void {
    $mbConfig->packageDirectories([
        __DIR__ . '/packages',
        __DIR__ . '/projects',
    ]);

    // exclude specific packages
    $mbConfig->packageDirectoriesExcludes([__DIR__ . '/packages/secret-package']);
};
```

### Customizing merge output

These options shape what `vendor/bin/monorepo-builder merge` writes into the root `composer.json`.

#### Append / remove data after merge

```php
use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use Symplify\MonorepoBuilder\Config\MBConfig;
use Symplify\MonorepoBuilder\ValueObject\Option;

return static function (MBConfig $mbConfig): void {
    // add data after merge (supports any composer.json key)
    $mbConfig->dataToAppend([
        ComposerJsonSection::AUTOLOAD_DEV => [
            'psr-4' => [
                'Symplify\Tests\\' => 'tests',
            ],
        ],
        ComposerJsonSection::REQUIRE_DEV => [
            'phpstan/phpstan' => '^2.1',
        ],
    ]);

    // remove data after merge
    $mbConfig->dataToRemove([
        ComposerJsonSection::REQUIRE => [
            // removed by key, version is irrelevant
            'phpunit/phpunit' => '*',
        ],
        ComposerJsonSection::REPOSITORIES => [
            Option::REMOVE_COMPLETELY,
        ],
    ]);
};
```

#### Section ordering

By default, the original key order of root `composer.json` is preserved. To enforce a specific order:

```php
use Symplify\MonorepoBuilder\Config\MBConfig;
use Symplify\MonorepoBuilder\Merge\JsonSchema;

return static function (MBConfig $mbConfig): void {
    $mbConfig->composerSectionOrder(JsonSchema::getProperties());
};
```

#### Skip autoload merging for selected packages

By default, every internal package's `autoload` and `autoload-dev` PSR-4 entries are folded into the root `composer.json` so that `vendor/bin/phpunit` and other root-level tooling can resolve every namespace. The three scenarios below cover when you'd want to skip part or all of that merging — pick the one that matches your monorepo:

**Scenario 1 — Default monorepo of libraries.** No skip needed. The root `composer.json` `autoload` aggregates every internal library's PSR-4 mapping, so any namespace resolves from the root vendor.

**Scenario 2 — Mixed monorepo with libraries symlinked + apps not required from root.** When [`disablePackageReplace()`](#skip-the-package-replace-section) is on (libraries are real path-repo deps, Composer symlinks them into `vendor/`), the libraries' `autoload` is registered automatically by Composer via `vendor/composer/autoload_psr4.php`. Folding them into the root `composer.json` would duplicate that registration. Skip autoload merging for libraries only — apps' autoload still merges so root-level scripts can find them:

```php
use Symplify\MonorepoBuilder\Config\AutoloadSection;
use Symplify\MonorepoBuilder\Config\MBConfig;
use Symplify\MonorepoBuilder\Config\PackageType;

return static function (MBConfig $mbConfig): void {
    $mbConfig->disablePackageReplace();
    $mbConfig->disableAutoloadMerge(
        sections: [AutoloadSection::Autoload],
        forTypes: [PackageType::Library],
    );
};
```

Result: root `autoload` contains apps' PSR-4 entries but NOT libraries'. Root `autoload-dev` still aggregates everything (see "Why autoload-dev is independent" below).

**Scenario 3 — Custom merge.** To turn off both sections entirely (you handle merging yourself, e.g. via a custom decorator):

```php
use Symplify\MonorepoBuilder\Config\AutoloadSection;
use Symplify\MonorepoBuilder\Config\MBConfig;

return static function (MBConfig $mbConfig): void {
    $mbConfig->disableAutoloadMerge(
        sections: [AutoloadSection::Autoload, AutoloadSection::AutoloadDev],
        forTypes: [],
    );
};
```

Result: root `autoload` and `autoload-dev` are untouched by `monorepo-builder merge`.

**API reference:**
- `disableAutoloadMerge(array $sections, array $forTypes)` — both arguments are required.
  - `$sections`: a non-empty list of `AutoloadSection` cases (`Autoload`, `AutoloadDev`).
  - `$forTypes`: a list of composer.json `type` filter values. **Each element may be either a `PackageType` enum case (preferred for the four [Composer schema](https://getcomposer.org/doc/04-schema.md#type) types: `Library`, `Project`, `Metapackage`, `ComposerPlugin`) or a non-empty string (escape hatch for ecosystem types defined by `composer/installers` such as `'wordpress-plugin'`, `'drupal-module'`, `'symfony-bundle'`, and for user-defined custom types).** Mixing enum cases and strings in the same call is allowed; multiple types are OR-matched. The two filter channels are intentionally distinct: pass an **empty array** (`forTypes: []`) to skip every package regardless of type, OR pass a non-empty list of types to skip ONLY packages whose `composer.json` declares the matching `type` literally. Composer's "missing `type` defaults to library" rule does NOT extend the filter — a package without an explicit `type` field is NOT swept up by `forTypes: [PackageType::Library]`. If you want the filter to catch an untyped package, declare `type: library` (or whichever) in that package's `composer.json`.
- Repeated calls follow last-call-wins semantics PER section. Calls touching different sections compose; calls touching the same section override.

**Migrating from the previous binary API:** The earlier zero-argument form `$mbConfig->disableAutoloadMerge();` continues to work but is deprecated and emits an `E_USER_DEPRECATED` notice. It maps to the full-kill behavior — equivalent to `disableAutoloadMerge(sections: [AutoloadSection::Autoload, AutoloadSection::AutoloadDev], forTypes: [])`. Update existing config files at your convenience. The legacy `MBConfig::isAutoloadMergeDisabled()` getter is also kept as a deprecated convenience that returns `true` only when both sections are configured to skip merging for all packages — prefer `MBConfig::shouldSkipAutoload($packageType)` and `MBConfig::shouldSkipAutoloadDev($packageType)` for new code.

##### Why autoload-dev is independent

Composer treats `autoload-dev` as a **root-only** section: dev autoload entries from path-repo dependencies are NEVER registered in the consumer's `vendor/composer/autoload_psr4.php`. (See [Composer schema docs — `autoload-dev`](https://getcomposer.org/doc/04-schema.md#autoload-dev).)

Practical consequence: if your CI runs `vendor/bin/phpunit` from the monorepo root and expects to discover library test classes, those test classes are reachable ONLY because `monorepo-builder merge` has folded each library's `autoload-dev` PSR-4 into the root `composer.json`. Skipping `AutoloadSection::AutoloadDev` from root merge therefore breaks cross-package PHPUnit discovery — skip it only when you're handling test discovery another way.

#### Skip the package-replace section

By default, `monorepo-builder merge` writes a `replace` section into the root `composer.json` listing every internal package at `self.version`. This is correct for monorepos that publish a single combined dependency surface — Composer then refuses to install any external copy of those packages.

Some monorepos do NOT want this:

- Apps that require their own internal libraries via `path` repositories and rely on Composer's symlink installation (the `replace` entry would short-circuit the symlink)
- Monorepos with mixed `type: library` packages and `type: project` apps where the apps need real installs of the libs

To skip writing the `replace` section entirely:

```php
use Symplify\MonorepoBuilder\Config\MBConfig;

return static function (MBConfig $mbConfig): void {
    $mbConfig->disablePackageReplace();
};
```

This pairs naturally with [Scenario 2 of the autoload skip section](#skip-autoload-merging-for-selected-packages) above. With both opt-outs on, your `path`-repository-based libraries get symlink-installed by Composer and only your apps' autoload entries land in the root `composer.json`.

### Customizing the release pipeline

These options shape what `vendor/bin/monorepo-builder release` does on each invocation.

#### Custom workers

`TagVersionReleaseWorker` and `PushTagReleaseWorker` are enabled by default. Add more workers or customize the order:

```php
use Symplify\MonorepoBuilder\Config\MBConfig;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\AddTagToChangelogReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\PushNextDevReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\PushTagReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\SetCurrentMutualDependenciesReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\SetNextMutualDependenciesReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\TagVersionReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\UpdateBranchAliasReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\UpdateReplaceReleaseWorker;

return static function (MBConfig $mbConfig): void {
    $mbConfig->workers([
        UpdateReplaceReleaseWorker::class,
        SetCurrentMutualDependenciesReleaseWorker::class,
        AddTagToChangelogReleaseWorker::class,
        TagVersionReleaseWorker::class,
        PushTagReleaseWorker::class,
        SetNextMutualDependenciesReleaseWorker::class,
        UpdateBranchAliasReleaseWorker::class,
        PushNextDevReleaseWorker::class,
    ]);
};
```

To disable the default workers (and define your pipeline from scratch):

```php
return static function (MBConfig $mbConfig): void {
    $mbConfig->disableDefaultWorkers();
};
```

You can also add custom workers by implementing `ReleaseWorkerInterface`.

#### Branch-aware tag validation (LTS)

If you maintain multiple version lines, the release command may reject older versions because it compares against the most recent tag globally. Enable branch-aware validation to compare only within the same major version:

```php
use Symplify\MonorepoBuilder\Config\MBConfig;
use Symplify\MonorepoBuilder\Contract\Git\TagResolverInterface;
use Symplify\MonorepoBuilder\Git\BranchAwareTagResolver;

return static function (MBConfig $mbConfig): void {
    $services = $mbConfig->services();
    $services->set(BranchAwareTagResolver::class);
    $services->alias(TagResolverInterface::class, BranchAwareTagResolver::class);
};
```

#### Custom alias format

`vendor/bin/monorepo-builder package-alias` writes a `branch-alias` entry into every package `composer.json`. To override the format string used:

```php
use Symplify\MonorepoBuilder\Config\MBConfig;

return static function (MBConfig $mbConfig): void {
    // default: "<major>.<minor>-dev"
    $mbConfig->packageAliasFormat('<major>.<minor>.x-dev');
};
```

## Package Splitting

To split packages into separate repositories, use [symplify/github-action-monorepo-split](https://github.com/symplify/github-action-monorepo-split) with GitHub Actions.
