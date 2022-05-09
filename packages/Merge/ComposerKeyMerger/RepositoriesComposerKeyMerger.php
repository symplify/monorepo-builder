<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge\ComposerKeyMerger;

use MonorepoBuilder20220509\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Merge\Arrays\SortedParameterMerger;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerKeyMergerInterface;
final class RepositoriesComposerKeyMerger implements \Symplify\MonorepoBuilder\Merge\Contract\ComposerKeyMergerInterface
{
    /**
     * @var \Symplify\MonorepoBuilder\Merge\Arrays\SortedParameterMerger
     */
    private $sortedParameterMerger;
    public function __construct(\Symplify\MonorepoBuilder\Merge\Arrays\SortedParameterMerger $sortedParameterMerger)
    {
        $this->sortedParameterMerger = $sortedParameterMerger;
    }
    public function merge(\MonorepoBuilder20220509\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $mainComposerJson, \MonorepoBuilder20220509\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $newComposerJson) : void
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
