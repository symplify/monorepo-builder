<?php

declare (strict_types=1);
namespace MonorepoBuilder20210804\Symplify\ComposerJsonManipulator\Bundle;

use MonorepoBuilder20210804\Symfony\Component\HttpKernel\Bundle\Bundle;
use MonorepoBuilder20210804\Symplify\ComposerJsonManipulator\DependencyInjection\Extension\ComposerJsonManipulatorExtension;
final class ComposerJsonManipulatorBundle extends \MonorepoBuilder20210804\Symfony\Component\HttpKernel\Bundle\Bundle
{
    protected function createContainerExtension() : ?\MonorepoBuilder20210804\Symfony\Component\DependencyInjection\Extension\ExtensionInterface
    {
        return new \MonorepoBuilder20210804\Symplify\ComposerJsonManipulator\DependencyInjection\Extension\ComposerJsonManipulatorExtension();
    }
}
