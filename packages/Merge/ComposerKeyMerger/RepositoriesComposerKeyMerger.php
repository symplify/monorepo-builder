<?php

declare (strict_types=1);
namespace MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\ComposerKeyMerger;

use MonorepoBuilder20210705\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\Arrays\SortedParameterMerger;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\Contract\ComposerKeyMergerInterface;
final class RepositoriesComposerKeyMerger implements \MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\Contract\ComposerKeyMergerInterface
{
    /**
     * @var \Symplify\MonorepoBuilder\Merge\Arrays\SortedParameterMerger
     */
    private $sortedParameterMerger;
    public function __construct(\MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\Arrays\SortedParameterMerger $sortedParameterMerger)
    {
        $this->sortedParameterMerger = $sortedParameterMerger;
    }
    public function merge(\MonorepoBuilder20210705\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $mainComposerJson, \MonorepoBuilder20210705\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $newComposerJson) : void
    {
        if ($newComposerJson->getRepositories() === []) {
            return;
        }
        $repositories = $this->sortedParameterMerger->mergeRecursiveAndSort($mainComposerJson->getRepositories(), $newComposerJson->getRepositories());
        // uniquate special cases, ref https://github.com/symplify/symplify/issues/1197
        $repositories = \array_unique($repositories, \SORT_REGULAR);
        // remove keys
        $repositories = \array_values($repositories);
        $mainComposerJson->setRepositories($repositories);
    }
}
