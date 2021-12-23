<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge\Application;

use MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Merge\ComposerJsonMerger;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerJsonDecoratorInterface;
use MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo;
/**
 * @see \Symplify\MonorepoBuilder\Tests\Merge\Application\MergedAndDecoratedComposerJsonFactoryTest
 */
final class MergedAndDecoratedComposerJsonFactory
{
    /**
     * @var \Symplify\MonorepoBuilder\Merge\ComposerJsonMerger
     */
    private $composerJsonMerger;
    /**
     * @var \Symplify\MonorepoBuilder\Merge\Contract\ComposerJsonDecoratorInterface[]
     */
    private $composerJsonDecorators;
    /**
     * @param ComposerJsonDecoratorInterface[] $composerJsonDecorators
     */
    public function __construct(\Symplify\MonorepoBuilder\Merge\ComposerJsonMerger $composerJsonMerger, array $composerJsonDecorators)
    {
        $this->composerJsonMerger = $composerJsonMerger;
        $this->composerJsonDecorators = $composerJsonDecorators;
    }
    /**
     * @param SmartFileInfo[] $packageFileInfos
     */
    public function createFromRootConfigAndPackageFileInfos(\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $mainComposerJson, array $packageFileInfos) : void
    {
        $mergedAndDecoratedComposerJson = $this->mergePackageFileInfosAndDecorate($packageFileInfos);
        $this->composerJsonMerger->mergeJsonToRoot($mainComposerJson, $mergedAndDecoratedComposerJson);
    }
    /**
     * @param SmartFileInfo[] $packageFileInfos
     */
    private function mergePackageFileInfosAndDecorate(array $packageFileInfos) : \MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson
    {
        $mergedComposerJson = $this->composerJsonMerger->mergeFileInfos($packageFileInfos);
        foreach ($this->composerJsonDecorators as $composerJsonDecorator) {
            $composerJsonDecorator->decorate($mergedComposerJson);
        }
        return $mergedComposerJson;
    }
}
