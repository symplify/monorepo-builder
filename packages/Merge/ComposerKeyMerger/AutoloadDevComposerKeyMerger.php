<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge\ComposerKeyMerger;

use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Merge\Arrays\SortedParameterMerger;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerKeyMergerInterface;
final class AutoloadDevComposerKeyMerger implements ComposerKeyMergerInterface
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
        if ($newComposerJson->getAutoloadDev() === []) {
            return;
        }
        $autoloadDev = $this->sortedParameterMerger->mergeRecursiveAndSort($mainComposerJson->getAutoloadDev(), $newComposerJson->getAutoloadDev());
        $mainComposerJson->setAutoloadDev($autoloadDev);
    }
}
