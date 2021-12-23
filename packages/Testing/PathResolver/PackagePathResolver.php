<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Testing\PathResolver;

use MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo;
/**
 * @see \Symplify\MonorepoBuilder\Tests\Testing\PathResolver\PackagePathResolverTest
 */
final class PackagePathResolver
{
    /**
     * See https://getcomposer.org/doc/05-repositories.md#path
     */
    public function resolveRelativePathToLocalPackage(\MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo $rootComposerFileInfo, \MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo $packageComposerFileInfo) : string
    {
        $relativeFolderPathToLocalPackage = $this->resolveRelativeFolderPathToLocalPackage($rootComposerFileInfo, $packageComposerFileInfo);
        $relativeDirectoryToRoot = $this->resolveRelativeDirectoryToRoot($rootComposerFileInfo, $packageComposerFileInfo);
        return $relativeFolderPathToLocalPackage . $relativeDirectoryToRoot;
    }
    /**
     * See https://getcomposer.org/doc/05-repositories.md#path
     */
    public function resolveRelativeFolderPathToLocalPackage(\MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo $rootComposerFileInfo, \MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo $packageComposerFileInfo) : string
    {
        $currentDirectory = \dirname($packageComposerFileInfo->getRealPath());
        $nestingLevel = 0;
        while ($currentDirectory . '/composer.json' !== $rootComposerFileInfo->getRealPath()) {
            ++$nestingLevel;
            $currentDirectory = \dirname($currentDirectory);
        }
        return \str_repeat('../', $nestingLevel);
    }
    public function resolveRelativeDirectoryToRoot(\MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo $rootComposerFileInfo, \MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo $packageComposerFileInfo) : string
    {
        $rootDirectory = \dirname($rootComposerFileInfo->getRealPath());
        return \dirname($packageComposerFileInfo->getRelativeFilePathFromDirectory($rootDirectory));
    }
}
