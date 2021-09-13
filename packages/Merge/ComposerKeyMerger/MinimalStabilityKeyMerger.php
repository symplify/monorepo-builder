<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge\ComposerKeyMerger;

use MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerKeyMergerInterface;
final class MinimalStabilityKeyMerger implements \Symplify\MonorepoBuilder\Merge\Contract\ComposerKeyMergerInterface
{
    /**
     * @param \Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $mainComposerJson
     * @param \Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $newComposerJson
     */
    public function merge($mainComposerJson, $newComposerJson) : void
    {
        if ($newComposerJson->getMinimumStability() === null) {
            return;
        }
        $mainComposerJson->setMinimumStability($newComposerJson->getMinimumStability());
    }
}
