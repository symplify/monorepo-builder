<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge\Application;

use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Merge\ComposerJsonMerger;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerJsonDecoratorInterface;
use MonorepoBuilderPrefix202408\Symplify\SmartFileSystem\SmartFileInfo;
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
        $mergedAndDecoratedComposerJson = $this->mergePackageFileInfosAndDecorate($mainComposerJson, $packageFileInfos);
        $this->composerJsonMerger->mergeJsonToRoot($mainComposerJson, $mergedAndDecoratedComposerJson);
    }
    /**
     * @param SmartFileInfo[] $packageFileInfos
     */
    private function mergePackageFileInfosAndDecorate(ComposerJson $mainComposerJson, array $packageFileInfos) : ComposerJson
    {
        $mergedComposerJson = $this->composerJsonMerger->mergeFileInfos($mainComposerJson, $packageFileInfos);
        foreach ($this->composerJsonDecorators as $composerJsonDecorator) {
            $composerJsonDecorator->decorate($mergedComposerJson);
        }
        return $mergedComposerJson;
    }
}
