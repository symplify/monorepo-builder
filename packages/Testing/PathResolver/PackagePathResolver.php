<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Testing\PathResolver;

use MonorepoBuilderPrefix202311\Symplify\SmartFileSystem\SmartFileInfo;
/**
 * @see \Symplify\MonorepoBuilder\Tests\Testing\PathResolver\PackagePathResolverTest
 */
final class PackagePathResolver
{
    /**
     * See https://getcomposer.org/doc/05-repositories.md#path
     */
    public function resolveRelativeFolderPathToLocalPackage(SmartFileInfo $rootComposerFileInfo, SmartFileInfo $packageComposerFileInfo) : string
    {
        $currentDirectory = \dirname($packageComposerFileInfo->getRealPath());
        $nestingLevel = 0;
        while ($currentDirectory . '/composer.json' !== $rootComposerFileInfo->getRealPath()) {
            ++$nestingLevel;
            $currentDirectory = \dirname($currentDirectory);
        }
        return \str_repeat('../', $nestingLevel);
    }
    public function resolveRelativeDirectoryToRoot(SmartFileInfo $rootComposerFileInfo, SmartFileInfo $packageComposerFileInfo) : string
    {
        $rootDirectory = \dirname($rootComposerFileInfo->getRealPath());
        return \dirname($packageComposerFileInfo->getRelativeFilePathFromDirectory($rootDirectory));
    }
}
