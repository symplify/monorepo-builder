<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge\Contract;

use MonorepoBuilder20211210\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
interface ComposerJsonDecoratorInterface
{
    public function decorate(\MonorepoBuilder20211210\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $composerJson) : void;
}
