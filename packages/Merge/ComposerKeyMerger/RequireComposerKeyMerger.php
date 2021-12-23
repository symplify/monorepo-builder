<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge\ComposerKeyMerger;

use MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Merge\Arrays\SortedParameterMerger;
use Symplify\MonorepoBuilder\Merge\Cleaner\RequireRequireDevDuplicateCleaner;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerKeyMergerInterface;
final class RequireComposerKeyMerger implements \Symplify\MonorepoBuilder\Merge\Contract\ComposerKeyMergerInterface
{
    /**
     * @var \Symplify\MonorepoBuilder\Merge\Arrays\SortedParameterMerger
     */
    private $sortedParameterMerger;
    /**
     * @var \Symplify\MonorepoBuilder\Merge\Cleaner\RequireRequireDevDuplicateCleaner
     */
    private $requireRequireDevDuplicateCleaner;
    public function __construct(\Symplify\MonorepoBuilder\Merge\Arrays\SortedParameterMerger $sortedParameterMerger, \Symplify\MonorepoBuilder\Merge\Cleaner\RequireRequireDevDuplicateCleaner $requireRequireDevDuplicateCleaner)
    {
        $this->sortedParameterMerger = $sortedParameterMerger;
        $this->requireRequireDevDuplicateCleaner = $requireRequireDevDuplicateCleaner;
    }
    public function merge(\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $mainComposerJson, \MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $newComposerJson) : void
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
