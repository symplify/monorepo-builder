<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\FileSystem;

use MonorepoBuilder202209\Symplify\ComposerJsonManipulator\ComposerJsonFactory;
use MonorepoBuilder202209\Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager;
use MonorepoBuilder202209\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Finder\PackageComposerFinder;
use MonorepoBuilder202209\Symplify\SmartFileSystem\SmartFileInfo;
use MonorepoBuilder202209\Symplify\SymplifyKernel\Exception\ShouldNotHappenException;
final class ComposerJsonProvider
{
    /**
     * @var \Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager
     */
    private $jsonFileManager;
    /**
     * @var \Symplify\MonorepoBuilder\Finder\PackageComposerFinder
     */
    private $packageComposerFinder;
    /**
     * @var \Symplify\ComposerJsonManipulator\ComposerJsonFactory
     */
    private $composerJsonFactory;
    public function __construct(JsonFileManager $jsonFileManager, PackageComposerFinder $packageComposerFinder, ComposerJsonFactory $composerJsonFactory)
    {
        $this->jsonFileManager = $jsonFileManager;
        $this->packageComposerFinder = $packageComposerFinder;
        $this->composerJsonFactory = $composerJsonFactory;
    }
    /**
     * @return SmartFileInfo[]
     */
    public function getPackagesComposerFileInfos() : array
    {
        return $this->packageComposerFinder->getPackageComposerFiles();
    }
    /**
     * @return SmartFileInfo[]
     */
    public function getRootAndPackageFileInfos() : array
    {
        return \array_merge($this->getPackagesComposerFileInfos(), [$this->packageComposerFinder->getRootPackageComposerFile()]);
    }
    /**
     * @return ComposerJson[]
     */
    public function getPackageComposerJsons() : array
    {
        $packageComposerJsons = [];
        foreach ($this->getPackagesComposerFileInfos() as $packagesComposerFileInfo) {
            $packageComposerJsons[] = $this->composerJsonFactory->createFromFileInfo($packagesComposerFileInfo);
        }
        return $packageComposerJsons;
    }
    /**
     * @return string[]
     */
    public function getPackageNames() : array
    {
        $packageNames = [];
        foreach ($this->getPackagesComposerFileInfos() as $packagesComposerFileInfo) {
            $packageComposerJson = $this->composerJsonFactory->createFromFileInfo($packagesComposerFileInfo);
            $packageName = $packageComposerJson->getName();
            if (!\is_string($packageName)) {
                continue;
            }
            $packageNames[] = $packageName;
        }
        return $packageNames;
    }
    public function getPackageFileInfoByName(string $packageName) : SmartFileInfo
    {
        foreach ($this->getPackagesComposerFileInfos() as $packagesComposerFileInfo) {
            $json = $this->jsonFileManager->loadFromFileInfo($packagesComposerFileInfo);
            if (!isset($json['name'])) {
                continue;
            }
            if ($json['name'] !== $packageName) {
                continue;
            }
            return $packagesComposerFileInfo;
        }
        throw new ShouldNotHappenException();
    }
    public function getRootComposerJson() : ComposerJson
    {
        $rootFileInfo = $this->packageComposerFinder->getRootPackageComposerFile();
        return $this->composerJsonFactory->createFromFileInfo($rootFileInfo);
    }
}
