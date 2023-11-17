<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge;

use Symplify\MonorepoBuilder\ComposerJsonManipulator\ComposerJsonFactory;
use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Merge\Configuration\MergedPackagesCollector;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerPathNormalizerInterface;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerKeyMergerInterface;
use MonorepoBuilderPrefix202311\Symplify\SmartFileSystem\SmartFileInfo;
final class ComposerJsonMerger
{
    /**
     * @var \Symplify\MonorepoBuilder\ComposerJsonManipulator\ComposerJsonFactory
     */
    private $composerJsonFactory;
    /**
     * @var \Symplify\MonorepoBuilder\Merge\Configuration\MergedPackagesCollector
     */
    private $mergedPackagesCollector;
    /**
     * @var ComposerKeyMergerInterface[]
     */
    private $composerKeyMergers;
    /**
     * @var ComposerPathNormalizerInterface[]
     */
    private $composerPathNormalizers;
    /**
     * @param ComposerKeyMergerInterface[] $composerKeyMergers
     * @param ComposerPathNormalizerInterface[] $composerPathNormalizers
     */
    public function __construct(ComposerJsonFactory $composerJsonFactory, MergedPackagesCollector $mergedPackagesCollector, array $composerKeyMergers, array $composerPathNormalizers)
    {
        $this->composerJsonFactory = $composerJsonFactory;
        $this->mergedPackagesCollector = $mergedPackagesCollector;
        $this->composerKeyMergers = $composerKeyMergers;
        $this->composerPathNormalizers = $composerPathNormalizers;
    }
    /**
     * @param SmartFileInfo[] $composerPackagesFileInfos
     */
    public function mergeFileInfos(array $composerPackagesFileInfos) : ComposerJson
    {
        $mainComposerJson = $this->composerJsonFactory->createFromArray([]);
        foreach ($composerPackagesFileInfos as $composerPackageFileInfo) {
            $packageComposerJson = $this->composerJsonFactory->createFromFileInfo($composerPackageFileInfo);
            $this->mergeJsonToRootWithPackageFileInfo($mainComposerJson, $packageComposerJson, $composerPackageFileInfo);
        }
        return $mainComposerJson;
    }
    public function mergeJsonToRoot(ComposerJson $mainComposerJson, ComposerJson $newComposerJson) : void
    {
        $name = $newComposerJson->getName();
        if ($name !== null) {
            $this->mergedPackagesCollector->addPackage($name);
        }
        foreach ($this->composerKeyMergers as $composerKeyMerger) {
            $composerKeyMerger->merge($mainComposerJson, $newComposerJson);
        }
    }
    private function mergeJsonToRootWithPackageFileInfo(ComposerJson $mainComposerJson, ComposerJson $newComposerJson, SmartFileInfo $packageFileInfo) : void
    {
        // prepare paths before autolaod merging
        foreach ($this->composerPathNormalizers as $composerPathNormalizer) {
            $composerPathNormalizer->normalizePaths($newComposerJson, $packageFileInfo);
        }
        $this->mergeJsonToRoot($mainComposerJson, $newComposerJson);
    }
}
