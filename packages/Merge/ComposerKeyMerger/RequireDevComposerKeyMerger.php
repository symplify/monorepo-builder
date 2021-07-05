<?php

declare (strict_types=1);
namespace MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\ComposerKeyMerger;

use MonorepoBuilder20210705\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\Arrays\SortedParameterMerger;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\Cleaner\RequireRequireDevDuplicateCleaner;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\Contract\ComposerKeyMergerInterface;
final class RequireDevComposerKeyMerger implements \MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\Contract\ComposerKeyMergerInterface
{
    /**
     * @var \Symplify\MonorepoBuilder\Merge\Arrays\SortedParameterMerger
     */
    private $sortedParameterMerger;
    /**
     * @var \Symplify\MonorepoBuilder\Merge\Cleaner\RequireRequireDevDuplicateCleaner
     */
    private $requireRequireDevDuplicateCleaner;
    public function __construct(\MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\Arrays\SortedParameterMerger $sortedParameterMerger, \MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\Cleaner\RequireRequireDevDuplicateCleaner $requireRequireDevDuplicateCleaner)
    {
        $this->sortedParameterMerger = $sortedParameterMerger;
        $this->requireRequireDevDuplicateCleaner = $requireRequireDevDuplicateCleaner;
    }
    public function merge(\MonorepoBuilder20210705\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $mainComposerJson, \MonorepoBuilder20210705\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $newComposerJson) : void
    {
        if ($newComposerJson->getRequireDev() === []) {
            return;
        }
        $requireDev = $this->sortedParameterMerger->mergeAndSort($newComposerJson->getRequireDev(), $mainComposerJson->getRequireDev());
        $requireDev = $this->requireRequireDevDuplicateCleaner->unsetPackageFromRequire($mainComposerJson, $requireDev);
        $mainComposerJson->setRequireDev($requireDev);
    }
}
