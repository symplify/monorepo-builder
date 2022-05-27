<?php

declare (strict_types=1);
namespace MonorepoBuilder20220527\Symplify\VendorPatches\Composer;

use MonorepoBuilder20220527\Symplify\Astral\Exception\ShouldNotHappenException;
use MonorepoBuilder20220527\Symplify\ComposerJsonManipulator\ComposerJsonFactory;
use MonorepoBuilder20220527\Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager;
use MonorepoBuilder20220527\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use MonorepoBuilder20220527\Symplify\PackageBuilder\Yaml\ParametersMerger;
use MonorepoBuilder20220527\Symplify\SmartFileSystem\SmartFileInfo;
/**
 * @see \Symplify\VendorPatches\Tests\Composer\ComposerPatchesConfigurationUpdater\ComposerPatchesConfigurationUpdaterTest
 */
final class ComposerPatchesConfigurationUpdater
{
    /**
     * @var \Symplify\ComposerJsonManipulator\ComposerJsonFactory
     */
    private $composerJsonFactory;
    /**
     * @var \Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager
     */
    private $jsonFileManager;
    /**
     * @var \Symplify\PackageBuilder\Yaml\ParametersMerger
     */
    private $parametersMerger;
    public function __construct(\MonorepoBuilder20220527\Symplify\ComposerJsonManipulator\ComposerJsonFactory $composerJsonFactory, \MonorepoBuilder20220527\Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager $jsonFileManager, \MonorepoBuilder20220527\Symplify\PackageBuilder\Yaml\ParametersMerger $parametersMerger)
    {
        $this->composerJsonFactory = $composerJsonFactory;
        $this->jsonFileManager = $jsonFileManager;
        $this->parametersMerger = $parametersMerger;
    }
    /**
     * @param mixed[] $composerExtraPatches
     */
    public function updateComposerJson(string $composerJsonFilePath, array $composerExtraPatches) : \MonorepoBuilder20220527\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson
    {
        $extra = ['patches' => $composerExtraPatches];
        $composerJson = $this->composerJsonFactory->createFromFilePath($composerJsonFilePath);
        // merge "extra" section - deep merge is needed, so original patches are included
        $newExtra = $this->parametersMerger->merge($composerJson->getExtra(), $extra);
        $composerJson->setExtra($newExtra);
        return $composerJson;
    }
    /**
     * @param mixed[] $composerExtraPatches
     */
    public function updateComposerJsonAndPrint(string $composerJsonFilePath, array $composerExtraPatches) : void
    {
        $composerJson = $this->updateComposerJson($composerJsonFilePath, $composerExtraPatches);
        $fileInfo = $composerJson->getFileInfo();
        if (!$fileInfo instanceof \MonorepoBuilder20220527\Symplify\SmartFileSystem\SmartFileInfo) {
            throw new \MonorepoBuilder20220527\Symplify\Astral\Exception\ShouldNotHappenException();
        }
        $this->jsonFileManager->printComposerJsonToFilePath($composerJson, $fileInfo->getRealPath());
    }
}
