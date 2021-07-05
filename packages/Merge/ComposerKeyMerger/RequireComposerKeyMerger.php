<?php

declare (strict_types=1);
namespace MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\ComposerKeyMerger;

use MonorepoBuilder20210705\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\Arrays\SortedParameterMerger;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\Cleaner\RequireRequireDevDuplicateCleaner;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\Contract\ComposerKeyMergerInterface;
final class RequireComposerKeyMerger implements \MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\Contract\ComposerKeyMergerInterface
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
        if ($newComposerJson->getRequire() === []) {
            return;
        }
        $require = $this->sortedParameterMerger->mergeAndSort($newComposerJson->getRequire(), $mainComposerJson->getRequire());
        $mainComposerJson->setRequire($require);
        $requireDev = $this->requireRequireDevDuplicateCleaner->unsetPackageFromRequire($mainComposerJson, $mainComposerJson->getRequireDev());
        $mainComposerJson->setRequireDev($requireDev);
    }
}
