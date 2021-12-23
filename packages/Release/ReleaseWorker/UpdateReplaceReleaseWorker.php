<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PharIo\Version\Version;
use MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager;
use MonorepoBuilder20211223\Symplify\EasyCI\Exception\ShouldNotHappenException;
use Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo;
final class UpdateReplaceReleaseWorker implements \Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface
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
    public function work(\PharIo\Version\Version $version) : void
    {
        $rootComposerJson = $this->composerJsonProvider->getRootComposerJson();
        $replace = $rootComposerJson->getReplace();
        $packageNames = $this->composerJsonProvider->getPackageNames();
        $newReplace = [];
        foreach (\array_keys($replace) as $package) {
            if (!\in_array($package, $packageNames, \true)) {
                continue;
            }
            $newReplace[$package] = $version->getVersionString();
        }
        if ($replace === $newReplace) {
            return;
        }
        $rootComposerJson->setReplace($newReplace);
        $rootFileInfo = $rootComposerJson->getFileInfo();
        if (!$rootFileInfo instanceof \MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo) {
            throw new \MonorepoBuilder20211223\Symplify\EasyCI\Exception\ShouldNotHappenException();
        }
        $this->jsonFileManager->printJsonToFileInfo($rootComposerJson->getJsonArray(), $rootFileInfo);
    }
    public function getDescription(\PharIo\Version\Version $version) : string
    {
        return 'Update "replace" version in "composer.json" to new tag to avoid circular dependencies conflicts';
    }
}
