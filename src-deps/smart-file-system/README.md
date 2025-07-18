# Smart File System

[![Downloads total](https://img.shields.io/packagist/dt/symplify/smart-file-system.svg?style=flat-square)](https://packagist.org/packages/symplify/smart-file-system/stats)

## Install

```bash
composer require symplify/smart-file-system
```

## Use

### Does `SplFileInfo` exist?

The `SplFileInfo::getRealPath()` method returns absolute path to the file... [or `FALSE`, if the file doesn't exist](https://www.php.net/manual/en/splfileinfo.getrealpath.php). This default PHP behavior forces you to **check all `getRealPath()` calls**:

```php
$fileInfo = new SplFileInfo('non_existing_file.txt');

if ($fileInfo->getRealPath() === false) {
    // damn, the files doesn't exist
    // throw exception or whatever
    // everytime!
}

$fileRealPath = $fileInfo->getRealPath();
```

While this has a reason - e.g. to be sure the file was not deleted since the construction,
we actually have to call the method to find out the file was removed. Another annoyance is to tell this to static analyzers.

In reality, **it's very rare to work with file that was existing a while ago, but now is gone, without us doing it on purpose**. We usually use `SplFileInfo` to modify files or work with their paths.

**What if:**

- we could remove this problem and make sure **`getRealPath()` method always returns string**?
- get **an exception of non-existing file on SplFileInfo creation**?

### Introducing `SmartFileInfo`

```php
$fileInfo = new Symplify\SmartFileSystem\SmartFileInfo('non_existing_file.txt');
// throws Symplify\SmartFileSystem\Exception\FileNotFoundException
```

This class also bring new useful methods:

```php
// current directory (cwd()) is "/var/www"
$smartFileInfo = new Symplify\SmartFileSystem\SmartFileInfo('/var/www/src/ExistingFile.php');

echo $smartFileInfo->getBasenameWithoutSuffix();
// "ExistingFile"

echo $smartFileInfo->getRelativeFilePath();
// "src/ExistingFile.php"

echo $smartFileInfo->getRelativeDirectoryPath();
// "src"

echo $smartFileInfo->getRelativeFilePathFromDirectory('/var');
// "www/src/ExistingFile.php"
```

**It also fixes WTF behavior** of `Symfony\Component\Finder\SplFileInfo`. Which one? When you run e.g. `vendor/bin/ecs check src` and use `Finder`, the `getRelativeFilePath()` in Symfony now returns all the relative paths to `src`. Which is useless, mainly with multiple dirs like: `vendor/bin/ecs check src tests` both containing file `Post.php`.

```php
$smartFileInfo = new Symplify\SmartFileSystem\SmartFileInfo('/var/www/src/Post.php');

echo $smartFileInfo->getRelativeFilePathFromCwd();
// "src/Post.php"
```

### File name Matching

Last but not least, matching a file comes useful in excluding files (typical for tools like ECS, PHPStan, Psalm, Rector, PHP CS Fixer or PHP_CodeSniffer):

```php
$smartFileInfo = new Symplify\SmartFileSystem\SmartFileInfo('/var/www/src/PostRepository.php');

echo $smartFileInfo->endsWith('Repository.php');
// true

echo $smartFileInfo->doesFnmatch('*Repo*');
// true
```

### Smart FileSystem - Just like Symfony, just Better

New method - `readFile()` (to read files):

```php
$smartFileSystem = new Symplify\SmartFileSystem\SmartFileSystem();
$fileContent = $smartFileSystem->readFile(__DIR__ . '/SomeFile.php');
```

```php
// if you plan to use SmartFileInfo, use this
$smartFileInfo = $smartFileSystem->readFileToSmartFileInfo(__DIR__ . '/SomeFile.php');
```

### Sanitizer various files to `SmartFileInfo[]`

Do you have multiple file inputs that can mix-up?

```php
$files = [new SplFileInfo('someFile.php')];

$files = [new Symfony\Component\Finder\SplFileInfo('someFile.php', 'someFile', '')];

// or
$files = (new Symfony\Component\Finder\Finder())->files();

// or
$files = ['someFile.php'];
```

Later, you wan to actually work with the files:

```php
foreach ($files as $file) {
    // what methods do we have here
    // what kind of object?
    // is it even object or a string?
    $file->...
}
```

Use sanitized files, that **have united format you can rely on**:

```php
use Symplify\SmartFileSystem\Finder\FinderSanitizer;

$finderSanitizer = new FinderSanitizer();
$smartFileInfos = $finderSanitizer->sanitize($files);

// always array of Symplify\SmartFileSystem\SmartFileInfo
var_dump($smartFileInfos);
```

<br>

## Report Issues

In case you are experiencing a bug or want to request a new feature head over to the [Symplify monorepo issue tracker](https://github.com/symplify/symplify/issues)

## Contribute

The sources of this package are contained in the Symplify monorepo. We welcome contributions for this package on [symplify/symplify](https://github.com/symplify/symplify).
