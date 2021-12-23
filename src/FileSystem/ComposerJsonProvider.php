<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\FileSystem;

use MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ComposerJsonFactory;
use MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager;
use MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Finder\PackageComposerFinder;
use MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo;
use MonorepoBuilder20211223\Symplify\SymplifyKernel\Exception\ShouldNotHappenException;
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
    public function __construct(\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager $jsonFileManager, \Symplify\MonorepoBuilder\Finder\PackageComposerFinder $packageComposerFinder, \MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ComposerJsonFactory $composerJsonFactory)
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
    public function getPackageFileInfoByName(string $packageName) : \MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo
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
        throw new \MonorepoBuilder20211223\Symplify\SymplifyKernel\Exception\ShouldNotHappenException();
    }
    public function getRootComposerJson() : \MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson
    {
        $rootFileInfo = $this->packageComposerFinder->getRootPackageComposerFile();
        return $this->composerJsonFactory->createFromFileInfo($rootFileInfo);
    }
}
