<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge\ComposerKeyMerger;

use MonorepoBuilder20210708\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerKeyMergerInterface;
final class PreferStableKeyMerger implements \Symplify\MonorepoBuilder\Merge\Contract\ComposerKeyMergerInterface
{
    public function merge(\MonorepoBuilder20210708\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $mainComposerJson, \MonorepoBuilder20210708\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $newComposerJson) : void
    {
        if ($newComposerJson->getPreferStable() === null) {
            return;
        }
        $mainComposerJson->setPreferStable($newComposerJson->getPreferStable());
    }
}
