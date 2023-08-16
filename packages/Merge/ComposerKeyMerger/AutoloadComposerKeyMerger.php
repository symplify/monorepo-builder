<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge\ComposerKeyMerger;

use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Merge\Arrays\SortedParameterMerger;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerKeyMergerInterface;
final class AutoloadComposerKeyMerger implements ComposerKeyMergerInterface
{
    /**
     * @var \Symplify\MonorepoBuilder\Merge\Arrays\SortedParameterMerger
     */
    private $sortedParameterMerger;
    public function __construct(SortedParameterMerger $sortedParameterMerger)
    {
        $this->sortedParameterMerger = $sortedParameterMerger;
    }
    public function merge(ComposerJson $mainComposerJson, ComposerJson $newComposerJson) : void
    {
        if ($newComposerJson->getAutoload() === []) {
            return;
        }
        $autoload = $this->sortedParameterMerger->mergeRecursiveAndSort($mainComposerJson->getAutoload(), $newComposerJson->getAutoload());
        $mainComposerJson->setAutoload($autoload);
    }
}
