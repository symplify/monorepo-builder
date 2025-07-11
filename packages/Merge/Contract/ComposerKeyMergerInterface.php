<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge\Contract;

use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJson;
interface ComposerKeyMergerInterface
{
    public function merge(ComposerJson $mainComposerJson, ComposerJson $newComposerJson) : void;
}
