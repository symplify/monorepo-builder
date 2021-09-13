<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge\Application;

use MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Merge\ComposerJsonMerger;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerJsonDecoratorInterface;
use MonorepoBuilder20210913\Symplify\SmartFileSystem\SmartFileInfo;
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
     * @var mixed[]
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
    public function createFromRootConfigAndPackageFileInfos(\MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $mainComposerJson, array $packageFileInfos) : void
    {
        $mergedAndDecoratedComposerJson = $this->mergePackageFileInfosAndDecorate($packageFileInfos);
        $this->composerJsonMerger->mergeJsonToRoot($mainComposerJson, $mergedAndDecoratedComposerJson);
    }
    /**
     * @param SmartFileInfo[] $packageFileInfos
     */
    private function mergePackageFileInfosAndDecorate(array $packageFileInfos) : \MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson
    {
        $mergedComposerJson = $this->composerJsonMerger->mergeFileInfos($packageFileInfos);
        foreach ($this->composerJsonDecorators as $composerJsonDecorator) {
            $composerJsonDecorator->decorate($mergedComposerJson);
        }
        return $mergedComposerJson;
    }
}
