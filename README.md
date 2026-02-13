# Monorepo Builder

[![Downloads total](https://img.shields.io/packagist/dt/symplify/monorepo-builder.svg?style=flat-square)](https://packagist.org/packages/symplify/monorepo-builder)

A set of tools for managing PHP monorepos: merging `composer.json` files, validating package versions, releasing with automation, and more.

## Install

```bash
composer require monorepo-php/monorepo --dev
```

Requires PHP 8.2+. For PHP 8.1, use `symplify/monorepo-builder:^11.2` (no longer maintained).

## Quick Start

If you're new to monorepos, generate a basic structure:

```bash
vendor/bin/monorepo-builder init
```

All configuration goes in `monorepo-builder.php` at your project root.

## Configuration

### Package Directories

By default, packages are discovered from `./packages`. To customize:

```php
use Symplify\MonorepoBuilder\Config\MBConfig;

return static function (MBConfig $mbConfig): void {
    $mbConfig->packageDirectories([
        __DIR__ . '/packages',
        __DIR__ . '/projects',
    ]);

    // exclude specific packages
    $mbConfig->packageDirectoriesExcludes([__DIR__ . '/packages/secret-package']);
};
```

## Commands

### merge

Merges all sections from package `composer.json` files into the root `composer.json`. For the reverse direction, see [`propagate`](#propagate).

```bash
vendor/bin/monorepo-builder merge
```

**Behavior:**

- All sections are merged, including standard (`require`, `autoload`, etc.) and custom ones (`scripts-aliases`, `abandoned`, etc.)
- If a package appears in both `require` and `require-dev`, the `require` entry takes priority
- The original key order of the root `composer.json` is preserved; new sections are appended at the end

**Append and remove data after merge:**

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

**Custom section order:**

By default, the original key order is preserved. To enforce a specific order:

```php
use Symplify\MonorepoBuilder\Config\MBConfig;
use Symplify\MonorepoBuilder\Merge\JsonSchema;

return static function (MBConfig $mbConfig): void {
    $mbConfig->composerSectionOrder(JsonSchema::getProperties());
};
```

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

Propagates versions from root `composer.json` to all packages (the reverse of `merge`):

```bash
vendor/bin/monorepo-builder propagate
```

### package-alias

Updates the `branch-alias` in every package `composer.json` to match the current version:

```bash
vendor/bin/monorepo-builder package-alias
```

To customize the alias format:

```php
use Symplify\MonorepoBuilder\Config\MBConfig;

return static function (MBConfig $mbConfig): void {
    // default: "<major>.<minor>-dev"
    $mbConfig->packageAliasFormat('<major>.<minor>.x-dev');
};
```

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
# current v0.7.1 -> v0.7.2
vendor/bin/monorepo-builder release patch
```

**Configuring release workers:**

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

To disable the default workers:

```php
return static function (MBConfig $mbConfig): void {
    $mbConfig->disableDefaultWorkers();
};
```

You can also add custom workers by implementing `ReleaseWorkerInterface`.

**Branch-aware tag validation (LTS):**

If you maintain multiple version lines, the release command may reject older versions because it compares against the most recent tag globally. Enable branch-aware validation to compare only within the same major version:

```php
use Symplify\MonorepoBuilder\Config\MBConfig;
use Symplify\MonorepoBuilder\Git\BranchAwareTagResolver;
use Symplify\MonorepoBuilder\Contract\Git\TagResolverInterface;

return static function (MBConfig $mbConfig): void {
    $services = $mbConfig->services();
    $services->set(BranchAwareTagResolver::class);
    $services->alias(TagResolverInterface::class, BranchAwareTagResolver::class);
};
```

## Package Splitting

To split packages into separate repositories, use [symplify/github-action-monorepo-split](https://github.com/symplify/github-action-monorepo-split) with GitHub Actions.
