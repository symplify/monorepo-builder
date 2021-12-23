<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge\Validation;

use MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use MonorepoBuilder20211223\Symplify\SmartFileSystem\FileSystemGuard;
use MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo;
final class AutoloadPathValidator
{
    /**
     * @var \Symplify\SmartFileSystem\FileSystemGuard
     */
    private $fileSystemGuard;
    public function __construct(\MonorepoBuilder20211223\Symplify\SmartFileSystem\FileSystemGuard $fileSystemGuard)
    {
        $this->fileSystemGuard = $fileSystemGuard;
    }
    public function ensureAutoloadPathExists(\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $composerJson) : void
    {
        $composerJsonFileInfo = $composerJson->getFileInfo();
        if (!$composerJsonFileInfo instanceof \MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo) {
            return;
        }
        $autoloadDirectories = $composerJson->getAbsoluteAutoloadDirectories();
        foreach ($autoloadDirectories as $autoloadDirectory) {
            $message = \sprintf('In "%s"', $composerJsonFileInfo->getRelativeFilePathFromCwd());
            $this->fileSystemGuard->ensureDirectoryExists($autoloadDirectory, $message);
        }
    }
}
