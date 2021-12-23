<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Package;

use MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager;
use Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;
use Symplify\MonorepoBuilder\ValueObject\Package;
use MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo;
use MonorepoBuilder20211223\Symplify\SymplifyKernel\Exception\ShouldNotHappenException;
final class PackageProvider
{
    /**
     * @var \Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider
     */
    private $composerJsonProvider;
    /**
     * @var \Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager
     */
    private $jsonFileManager;
    public function __construct(\Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider $composerJsonProvider, \MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager $jsonFileManager)
    {
        $this->composerJsonProvider = $composerJsonProvider;
        $this->jsonFileManager = $jsonFileManager;
    }
    /**
     * @return Package[]
     */
    public function provide() : array
    {
        $packages = [];
        foreach ($this->composerJsonProvider->getPackagesComposerFileInfos() as $packagesComposerFileInfo) {
            $packageName = $this->detectNameFromFileInfo($packagesComposerFileInfo);
            $hasTests = \file_exists($packagesComposerFileInfo->getRealPathDirectory() . '/tests');
            $packages[] = new \Symplify\MonorepoBuilder\ValueObject\Package($packageName, $hasTests);
        }
        \usort($packages, function (\Symplify\MonorepoBuilder\ValueObject\Package $firstPackage, \Symplify\MonorepoBuilder\ValueObject\Package $secondPackage) : int {
            return $firstPackage->getShortName() <=> $secondPackage->getShortName();
        });
        return $packages;
    }
    private function detectNameFromFileInfo(\MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo $smartFileInfo) : string
    {
        $json = $this->jsonFileManager->loadFromFileInfo($smartFileInfo);
        if (!isset($json['name'])) {
            throw new \MonorepoBuilder20211223\Symplify\SymplifyKernel\Exception\ShouldNotHappenException();
        }
        return (string) $json['name'];
    }
}
