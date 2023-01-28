<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge\Validation;

use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJson;
use MonorepoBuilderPrefix202301\Symplify\SmartFileSystem\FileSystemGuard;
use MonorepoBuilderPrefix202301\Symplify\SmartFileSystem\SmartFileInfo;
final class AutoloadPathValidator
{
    /**
     * @var \Symplify\SmartFileSystem\FileSystemGuard
     */
    private $fileSystemGuard;
    public function __construct(FileSystemGuard $fileSystemGuard)
    {
        $this->fileSystemGuard = $fileSystemGuard;
    }
    public function ensureAutoloadPathExists(ComposerJson $composerJson) : void
    {
        $composerJsonFileInfo = $composerJson->getFileInfo();
        if (!$composerJsonFileInfo instanceof SmartFileInfo) {
            return;
        }
        $autoloadDirectories = $composerJson->getAbsoluteAutoloadDirectories();
        foreach ($autoloadDirectories as $autoloadDirectory) {
            $message = \sprintf('In "%s"', $composerJsonFileInfo->getRelativeFilePathFromCwd());
            $this->fileSystemGuard->ensureDirectoryExists($autoloadDirectory, $message);
        }
    }
}
