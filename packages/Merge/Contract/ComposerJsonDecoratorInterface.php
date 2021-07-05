<?php

declare (strict_types=1);
namespace MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\Contract;

use MonorepoBuilder20210705\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
interface ComposerJsonDecoratorInterface
{
    public function decorate(\MonorepoBuilder20210705\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $composerJson) : void;
}
