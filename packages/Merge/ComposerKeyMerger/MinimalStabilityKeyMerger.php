<?php

declare (strict_types=1);
namespace MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\ComposerKeyMerger;

use MonorepoBuilder20210705\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\Contract\ComposerKeyMergerInterface;
final class MinimalStabilityKeyMerger implements \MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\Contract\ComposerKeyMergerInterface
{
    public function merge(\MonorepoBuilder20210705\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $mainComposerJson, \MonorepoBuilder20210705\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $newComposerJson) : void
    {
        if ($newComposerJson->getMinimumStability() === null) {
            return;
        }
        $mainComposerJson->setMinimumStability($newComposerJson->getMinimumStability());
    }
}
