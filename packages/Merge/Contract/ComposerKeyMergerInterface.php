<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge\Contract;

use MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
interface ComposerKeyMergerInterface
{
    /**
     * @param \Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $mainComposerJson
     * @param \Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $newComposerJson
     */
    public function merge($mainComposerJson, $newComposerJson) : void;
}
