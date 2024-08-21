<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Merge\ComposerKeyMerger;

use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use Symplify\MonorepoBuilder\Merge\Arrays\SortedParameterMerger;
use Symplify\MonorepoBuilder\Merge\Cleaner\RequireRequireDevDuplicateCleaner;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerKeyMergerInterface;

final class RequireDevComposerKeyMerger implements ComposerKeyMergerInterface
{
    public function __construct(
        private SortedParameterMerger $sortedParameterMerger,
        private RequireRequireDevDuplicateCleaner $requireRequireDevDuplicateCleaner
    ) {
    }

    public function merge(ComposerJson $mainComposerJson, ComposerJson $newComposerJson): void
    {
        if ($newComposerJson->getRequireDev() === []) {
            return;
        }

        $requireDev = $this->sortedParameterMerger->mergeAndSort(ComposerJsonSection::REQUIRE_DEV,
            $newComposerJson->getRequireDev(),
            $mainComposerJson->getRequireDev()
        );

        $requireDev = $this->requireRequireDevDuplicateCleaner->unsetPackageFromRequire($mainComposerJson, $requireDev);

        $mainComposerJson->setRequireDev($requireDev);
    }
}
