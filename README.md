# Not only Composer tools to build a Monorepo

[![Downloads total](https://img.shields.io/packagist/dt/symplify/monorepo-builder.svg?style=flat-square)](https://packagist.org/packages/symplify/monorepo-builder)

Do you maintain a monorepo with multiple packages?

**This package has few useful tools, that will make that easier**.

## Install

```bash
# Latest version (PHP 8.2+)
composer require symplify/monorepo-builder --dev

# For PHP 8.1 (legacy version, no longer maintained)
composer require "symplify/monorepo-builder:^11.2" --dev
```

**Requirements:**
- PHP 8.2 or higher (for version 12.x)

**For older PHP versions:**
- Use version 11.x (no longer maintained)

## Usage

### 1. Are you New to Monorepo?

If you're new to monorepos, you can start with a basic setup using our initialization command:

```bash
vendor/bin/monorepo-builder init
```

This creates a basic monorepo structure with the necessary configuration files.


### 2. Merge local `composer.json` to the Root One

Merges configured sections to the root `composer.json`, so you can only edit `composer.json` of particular packages and let script to synchronize it.

Sections that will be merged from packages to root:

- `require` - Dependencies needed by packages
- `require-dev` - Development dependencies  
- `autoload` - PSR-4 autoloading configuration
- `autoload-dev` - Development autoloading configuration
- `repositories` - Package repositories
- `extra` - Extra configuration data
- `provide` - Virtual packages provided
- `authors` - Package authors information
- `minimum-stability` - Minimum package stability
- `prefer-stable` - Prefer stable packages
- `replace` - Packages replaced by this one

To merge run:

```bash
vendor/bin/monorepo-builder merge
```

<br>


```php
use Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use Symplify\MonorepoBuilder\Config\MBConfig;
use Symplify\MonorepoBuilder\ValueObject\Option;

return static function (MBConfig $mbConfig): void {
    // where are the packages located?
    $mbConfig->packageDirectories([
        // default value
        __DIR__ . '/packages',
        // custom
        __DIR__ . '/projects',
    ]);

    // how to skip packages in loaded directories?
    $mbConfig->packageDirectoriesExcludes([__DIR__ . '/packages/secret-package']);

    // "merge" command related

    // what extra parts to add after merge?
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

    $mbConfig->dataToRemove([
        ComposerJsonSection::REQUIRE => [
            // the line is removed by key, so version is irrelevant, thus *
            'phpunit/phpunit' => '*',
        ],
        ComposerJsonSection::REPOSITORIES => [
            // this will remove all repositories
            Option::REMOVE_COMPLETELY,
        ],
    ]);
};
```

### 3. Bump Package Inter-dependencies

Let's say you release `symplify/symplify` 4.0 and you need package to depend on version `^4.0` for each other:

```bash
vendor/bin/monorepo-builder bump-interdependency "^4.0"
```

### 4. Keep Synchronized Package Version

In synchronized monorepo, it's common to use same package version to prevent bugs and WTFs. So if one of your package uses `symfony/console` 3.4 and the other `symfony/console` 4.1, this will tell you:

```bash
vendor/bin/monorepo-builder validate
```

### 5. Keep Package Alias Up-To-Date

You can see this even if there is already version 3.0 out:

```json
{
    "extra": {
        "branch-alias": {
            "dev-master": "2.0-dev"
        }
    }
}
```

**Not good.** Get rid of this manual work and add this command to your release workflow:

```bash
vendor/bin/monorepo-builder package-alias
```

This will add alias `3.1-dev` to `composer.json` in each package.

If you prefer [`3.1.x-dev`](https://getcomposer.org/doc/articles/aliases.md#branch-alias) over default `3.1-dev`, you can configure it:

```php
use Symplify\MonorepoBuilder\Config\MBConfig;

return static function (MBConfig $mbConfig): void {
    // default: "<major>.<minor>-dev"
    $mbConfig->packageAliasFormat('<major>.<minor>.x-dev');
};
```

### 6. Split Directories to Git Repositories

You can split packages from your monorepo into separate repositories using GitHub Actions. Use [symplify/github-action-monorepo-split](https://github.com/symplify/github-action-monorepo-split) for this purpose.

For configuration examples, you can refer to the [GitHub Action workflow documentation](https://github.com/danharrin/monorepo-split-github-action).

### 7. Release Flow

When a new version of your package is released, you have to do many manual steps:

- bump mutual dependencies,
- tag this version,
- `git push` with tag,
- change `CHANGELOG.md` title *Unreleased* to `v<version> - Y-m-d` format
- bump alias and mutual dependencies to next version alias

But what if **you forget one or do it in wrong order**? Everything will crash!

The `release` command will make you safe:

```bash
vendor/bin/monorepo-builder release v7.0
```

And add the following release workers to your `monorepo-builder.php`:

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
    // release workers - in order to execute
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

These `TagVersionReleaseWorker` and `PushTagReleaseWorker` are enabled by default.
If you want to disable these default workers, you can use the following code.

```php
return static function (MBConfig $mbConfig): void {
    $mbConfig->disableDefaultWorkers();
};
```

You can also include your own workers. Just add services that implements `ReleaseWorkerInterface`.
Are you afraid to tag and push? Use `--dry-run` to see only descriptions:

```bash
vendor/bin/monorepo-builder release 7.0 --dry-run
```

Do you want to release next [patch version](https://semver.org/), e.g. current `v0.7.1` → next `v0.7.2`?

```bash
vendor/bin/monorepo-builder release patch
```

You can use `minor` and `major` too.

### 8. Branch-Aware Tag Validation for LTS Releases

If you maintain multiple version lines (LTS strategy), you can enable branch-aware tag validation to allow releasing older versions even when newer versions exist.

**The Problem:**

By default, the release command compares the new version against the most recent tag by commit date. This causes issues when:
- Main branch has `v3.0.0` (tagged last month)
- LTS branch `2.x` needs to release `v2.1.5` (new tag today)
- ❌ Validation fails: `2.1.5 < 3.0.0`

**The Solution:**

Enable branch-aware validation to compare only within the same major version:

```php
use Symplify\MonorepoBuilder\Config\MBConfig;
use Symplify\MonorepoBuilder\Git\BranchAwareTagResolver;
use Symplify\MonorepoBuilder\Contract\Git\TagResolverInterface;

return static function (MBConfig $mbConfig): void {
    $services = $mbConfig->services();

    // Enable branch-aware tag validation by using BranchAwareTagResolver
    $services->set(BranchAwareTagResolver::class);
    $services->alias(TagResolverInterface::class, BranchAwareTagResolver::class);
};
```

## Available Commands

Here are all available commands you can use with monorepo-builder:

- `init` - Creates empty monorepo directory and composer.json structure
- `merge` - Merge "composer.json" from all found packages to root one
- `bump-interdependency` - Bump dependency of split packages on each other
- `validate` - Validates synchronized versions in "composer.json" in all found packages
- `package-alias` - Updates branch alias in "composer.json" all found packages
- `propagate` - Propagate versions from root "composer.json" to all packages, the opposite of "merge" command
- `localize-composer-paths` - Set mutual package paths to local packages - use for pre-split package testing
- `release` - Perform release process with set Release Workers

To see detailed help for any command, run:
```bash
vendor/bin/monorepo-builder <command> --help
```
