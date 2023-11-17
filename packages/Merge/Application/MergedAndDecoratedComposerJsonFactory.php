<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge\Application;

use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Merge\ComposerJsonMerger;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerJsonDecoratorInterface;
use MonorepoBuilderPrefix202311\Symplify\SmartFileSystem\SmartFileInfo;
final class MergedAndDecoratedComposerJsonFactory
{
    /**
     * @var \Symplify\MonorepoBuilder\Merge\ComposerJsonMerger
     */
    private $composerJsonMerger;
    /**
     * @var ComposerJsonDecoratorInterface[]
     */
    private $composerJsonDecorators;
    /**
     * @param ComposerJsonDecoratorInterface[] $composerJsonDecorators
     */
    public function __construct(ComposerJsonMerger $composerJsonMerger, array $composerJsonDecorators)
    {
        $this->composerJsonMerger = $composerJsonMerger;
        $this->composerJsonDecorators = $composerJsonDecorators;
    }
    /**
     * @param SmartFileInfo[] $packageFileInfos
     */
    public function createFromRootConfigAndPackageFileInfos(ComposerJson $mainComposerJson, array $packageFileInfos) : void
    {
        $mergedAndDecoratedComposerJson = $this->mergePackageFileInfosAndDecorate($packageFileInfos);
        $this->composerJsonMerger->mergeJsonToRoot($mainComposerJson, $mergedAndDecoratedComposerJson);
    }
    /**
     * @param SmartFileInfo[] $packageFileInfos
     */
    private function mergePackageFileInfosAndDecorate(array $packageFileInfos) : ComposerJson
    {
        $mergedComposerJson = $this->composerJsonMerger->mergeFileInfos($packageFileInfos);
        foreach ($this->composerJsonDecorators as $composerJsonDecorator) {
            $composerJsonDecorator->decorate($mergedComposerJson);
        }
        return $mergedComposerJson;
    }
}
