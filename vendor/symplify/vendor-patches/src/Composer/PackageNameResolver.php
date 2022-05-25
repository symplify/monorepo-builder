<?php

declare (strict_types=1);
namespace MonorepoBuilder20220525\Symplify\VendorPatches\Composer;

use MonorepoBuilder20220525\Symplify\SmartFileSystem\FileSystemGuard;
use MonorepoBuilder20220525\Symplify\SmartFileSystem\Json\JsonFileSystem;
use MonorepoBuilder20220525\Symplify\SmartFileSystem\SmartFileInfo;
use MonorepoBuilder20220525\Symplify\SymplifyKernel\Exception\ShouldNotHappenException;
use MonorepoBuilder20220525\Symplify\VendorPatches\FileSystem\PathResolver;
/**
 * @see \Symplify\VendorPatches\Tests\Composer\PackageNameResolverTest
 */
final class PackageNameResolver
{
    /**
     * @var \Symplify\SmartFileSystem\Json\JsonFileSystem
     */
    private $jsonFileSystem;
    /**
     * @var \Symplify\VendorPatches\FileSystem\PathResolver
     */
    private $pathResolver;
    /**
     * @var \Symplify\SmartFileSystem\FileSystemGuard
     */
    private $fileSystemGuard;
    public function __construct(\MonorepoBuilder20220525\Symplify\SmartFileSystem\Json\JsonFileSystem $jsonFileSystem, \MonorepoBuilder20220525\Symplify\VendorPatches\FileSystem\PathResolver $pathResolver, \MonorepoBuilder20220525\Symplify\SmartFileSystem\FileSystemGuard $fileSystemGuard)
    {
        $this->jsonFileSystem = $jsonFileSystem;
        $this->pathResolver = $pathResolver;
        $this->fileSystemGuard = $fileSystemGuard;
    }
    public function resolveFromFileInfo(\MonorepoBuilder20220525\Symplify\SmartFileSystem\SmartFileInfo $vendorFile) : string
    {
        $packageComposerJsonFilePath = $this->getPackageComposerJsonFilePath($vendorFile);
        $composerJson = $this->jsonFileSystem->loadFilePathToJson($packageComposerJsonFilePath);
        if (!isset($composerJson['name'])) {
            throw new \MonorepoBuilder20220525\Symplify\SymplifyKernel\Exception\ShouldNotHappenException();
        }
        return $composerJson['name'];
    }
    private function getPackageComposerJsonFilePath(\MonorepoBuilder20220525\Symplify\SmartFileSystem\SmartFileInfo $vendorFileInfo) : string
    {
        $vendorPackageDirectory = $this->pathResolver->resolveVendorDirectory($vendorFileInfo);
        $packageComposerJsonFilePath = $vendorPackageDirectory . '/composer.json';
        $this->fileSystemGuard->ensureFileExists($packageComposerJsonFilePath, __METHOD__);
        return $packageComposerJsonFilePath;
    }
}
