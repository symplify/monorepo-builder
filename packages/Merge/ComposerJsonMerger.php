<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge;

use MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ComposerJsonFactory;
use MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Merge\Configuration\MergedPackagesCollector;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerKeyMergerInterface;
use Symplify\MonorepoBuilder\Merge\PathResolver\AutoloadPathNormalizer;
use MonorepoBuilder20210913\Symplify\SmartFileSystem\SmartFileInfo;
/**
 * @see \Symplify\MonorepoBuilder\Tests\Merge\ComposerJsonMerger\ComposerJsonMergerTest
 */
final class ComposerJsonMerger
{
    /**
     * @var \Symplify\ComposerJsonManipulator\ComposerJsonFactory
     */
    private $composerJsonFactory;
    /**
     * @var \Symplify\MonorepoBuilder\Merge\Configuration\MergedPackagesCollector
     */
    private $mergedPackagesCollector;
    /**
     * @var \Symplify\MonorepoBuilder\Merge\PathResolver\AutoloadPathNormalizer
     */
    private $autoloadPathNormalizer;
    /**
     * @var mixed[]
     */
    private $composerKeyMergers;
    /**
     * @param ComposerKeyMergerInterface[] $composerKeyMergers
     */
    public function __construct(\MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ComposerJsonFactory $composerJsonFactory, \Symplify\MonorepoBuilder\Merge\Configuration\MergedPackagesCollector $mergedPackagesCollector, \Symplify\MonorepoBuilder\Merge\PathResolver\AutoloadPathNormalizer $autoloadPathNormalizer, array $composerKeyMergers)
    {
        $this->composerJsonFactory = $composerJsonFactory;
        $this->mergedPackagesCollector = $mergedPackagesCollector;
        $this->autoloadPathNormalizer = $autoloadPathNormalizer;
        $this->composerKeyMergers = $composerKeyMergers;
    }
    /**
     * @param SmartFileInfo[] $composerPackageFileInfos
     */
    public function mergeFileInfos(array $composerPackageFileInfos) : \MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson
    {
        $mainComposerJson = $this->composerJsonFactory->createFromArray([]);
        foreach ($composerPackageFileInfos as $packageFileInfo) {
            $packageComposerJson = $this->composerJsonFactory->createFromFileInfo($packageFileInfo);
            $this->mergeJsonToRootWithPackageFileInfo($mainComposerJson, $packageComposerJson, $packageFileInfo);
        }
        return $mainComposerJson;
    }
    public function mergeJsonToRoot(\MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $mainComposerJson, \MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $newComposerJson) : void
    {
        $name = $newComposerJson->getName();
        if ($name !== null) {
            $this->mergedPackagesCollector->addPackage($name);
        }
        foreach ($this->composerKeyMergers as $composerKeyMerger) {
            $composerKeyMerger->merge($mainComposerJson, $newComposerJson);
        }
    }
    private function mergeJsonToRootWithPackageFileInfo(\MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $mainComposerJson, \MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $newComposerJson, \MonorepoBuilder20210913\Symplify\SmartFileSystem\SmartFileInfo $packageFileInfo) : void
    {
        // prepare paths before autolaod merging
        $this->autoloadPathNormalizer->normalizeAutoloadPaths($newComposerJson, $packageFileInfo);
        $this->mergeJsonToRoot($mainComposerJson, $newComposerJson);
    }
}
