<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Merge\ComposerKeyMerger;

use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use Symplify\MonorepoBuilder\Merge\Arrays\SortedParameterMerger;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerKeyMergerInterface;

final class AutoloadDevComposerKeyMerger implements ComposerKeyMergerInterface
{
    public function __construct(
        private SortedParameterMerger $sortedParameterMerger
    ) {
    }

    public function merge(ComposerJson $mainComposerJson, ComposerJson $newComposerJson): void
    {
        if ($newComposerJson->getAutoloadDev() === []) {
            return;
        }

        $autoloadDev = $this->sortedParameterMerger->mergeRecursiveAndSort(ComposerJsonSection::AUTOLOAD_DEV,
            $mainComposerJson->getAutoloadDev(),
            $newComposerJson->getAutoloadDev()
        );

        $mainComposerJson->setAutoloadDev($autoloadDev);
    }
}
