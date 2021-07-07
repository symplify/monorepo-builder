<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Release\ReleaseWorker;

use MonorepoBuilder20210707\PharIo\Version\Version;
use MonorepoBuilder20210707\Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager;
use MonorepoBuilder20210707\Symplify\EasyCI\Exception\ShouldNotHappenException;
use Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use MonorepoBuilder20210707\Symplify\SmartFileSystem\SmartFileInfo;
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
    public function __construct(\Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider $composerJsonProvider, \MonorepoBuilder20210707\Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager $jsonFileManager)
    {
        $this->composerJsonProvider = $composerJsonProvider;
        $this->jsonFileManager = $jsonFileManager;
    }
    public function work(\MonorepoBuilder20210707\PharIo\Version\Version $version) : void
    {
        $rootComposerJson = $this->composerJsonProvider->getRootComposerJson();
        $replace = $rootComposerJson->getReplace();
        $newReplace = [];
        foreach (\array_keys($replace) as $package) {
            $newReplace[$package] = $version->getVersionString();
        }
        if ($replace === $newReplace) {
            return;
        }
        $rootComposerJson->setReplace($newReplace);
        $rootFileInfo = $rootComposerJson->getFileInfo();
        if (!$rootFileInfo instanceof \MonorepoBuilder20210707\Symplify\SmartFileSystem\SmartFileInfo) {
            throw new \MonorepoBuilder20210707\Symplify\EasyCI\Exception\ShouldNotHappenException();
        }
        $this->jsonFileManager->printJsonToFileInfo($rootComposerJson->getJsonArray(), $rootFileInfo);
    }
    public function getDescription(\MonorepoBuilder20210707\PharIo\Version\Version $version) : string
    {
        return 'Update "replace" version in "composer.json" to new tag to avoid circular dependencies conflicts';
    }
}
