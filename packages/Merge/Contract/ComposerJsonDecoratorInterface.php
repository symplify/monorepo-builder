<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge\Contract;

use MonorepoBuilder20220207\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
interface ComposerJsonDecoratorInterface
{
    public function decorate(\MonorepoBuilder20220207\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $composerJson) : void;
}
