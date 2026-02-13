<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Merge\Application;

use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Merge\ComposerJsonMerger;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerJsonDecoratorInterface;
use Symplify\MonorepoBuilder\Merge\ComposerJsonDecorator\AppenderComposerJsonDecorator;
use Symplify\MonorepoBuilder\Merge\ComposerJsonDecorator\RemoverComposerJsonDecorator;
use Symplify\MonorepoBuilder\Merge\ComposerJsonDecorator\ReplaceSectionJsonDecorator;
use Symplify\MonorepoBuilder\Merge\ComposerJsonDecorator\RootRemoveComposerJsonDecorator;
use Symplify\MonorepoBuilder\Merge\ComposerJsonDecorator\RepositoryPathComposerJsonDecorator;
use Symplify\MonorepoBuilder\Merge\ComposerJsonDecorator\FilterOutDuplicatedRequireAndRequireDevJsonDecorator;
use Symplify\MonorepoBuilder\Merge\ComposerJsonDecorator\SortComposerJsonDecorator;
use Symplify\SmartFileSystem\SmartFileInfo;

final readonly class MergedAndDecoratedComposerJsonFactory
{
    /**
     * Explicit decorator execution order. Decorators not in this list run last
     * in their original order.
     *
     * @var list<class-string<ComposerJsonDecoratorInterface>>
     */
    private const DECORATOR_ORDER = [
        AppenderComposerJsonDecorator::class,
        RemoverComposerJsonDecorator::class,
        ReplaceSectionJsonDecorator::class,
        RootRemoveComposerJsonDecorator::class,
        RepositoryPathComposerJsonDecorator::class,
        FilterOutDuplicatedRequireAndRequireDevJsonDecorator::class,
        SortComposerJsonDecorator::class,
    ];

    /**
     * @var ComposerJsonDecoratorInterface[]
     */
    private array $sortedDecorators;

    /**
     * @param ComposerJsonDecoratorInterface[] $composerJsonDecorators
     */
    public function __construct(
        private ComposerJsonMerger $composerJsonMerger,
        array $composerJsonDecorators
    ) {
        $this->sortedDecorators = $this->sortDecorators($composerJsonDecorators);
    }

    /**
     * @param SmartFileInfo[] $packageFileInfos
     */
    public function createFromRootConfigAndPackageFileInfos(
        ComposerJson $mainComposerJson,
        array $packageFileInfos
    ): void {
        // mergeFileInfos modifies $mainComposerJson in place
        $this->composerJsonMerger->mergeFileInfos($mainComposerJson, $packageFileInfos);

        foreach ($this->sortedDecorators as $sortedDecorator) {
            $sortedDecorator->decorate($mainComposerJson);
        }
    }

    /**
     * @param ComposerJsonDecoratorInterface[] $decorators
     * @return ComposerJsonDecoratorInterface[]
     */
    private function sortDecorators(array $decorators): array
    {
        usort($decorators, fn(ComposerJsonDecoratorInterface $a, ComposerJsonDecoratorInterface $b): int => $this->getDecoratorOrder($a) <=> $this->getDecoratorOrder($b));

        return $decorators;
    }

    private function getDecoratorOrder(ComposerJsonDecoratorInterface $composerJsonDecorator): int
    {
        $position = array_search($composerJsonDecorator::class, self::DECORATOR_ORDER, true);

        // Unknown decorators go to the end, preserving relative order
        return $position === false ? PHP_INT_MAX : $position;
    }
}
