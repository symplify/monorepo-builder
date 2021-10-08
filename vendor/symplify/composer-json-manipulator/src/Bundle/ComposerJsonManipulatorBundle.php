<?php

declare (strict_types=1);
namespace MonorepoBuilder20211008\Symplify\ComposerJsonManipulator\Bundle;

use MonorepoBuilder20211008\Symfony\Component\HttpKernel\Bundle\Bundle;
use MonorepoBuilder20211008\Symplify\ComposerJsonManipulator\DependencyInjection\Extension\ComposerJsonManipulatorExtension;
final class ComposerJsonManipulatorBundle extends \MonorepoBuilder20211008\Symfony\Component\HttpKernel\Bundle\Bundle
{
    protected function createContainerExtension() : ?\MonorepoBuilder20211008\Symfony\Component\DependencyInjection\Extension\ExtensionInterface
    {
        return new \MonorepoBuilder20211008\Symplify\ComposerJsonManipulator\DependencyInjection\Extension\ComposerJsonManipulatorExtension();
    }
}
