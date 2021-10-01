<?php

declare (strict_types=1);
namespace MonorepoBuilder20211001\Symplify\ComposerJsonManipulator\Bundle;

use MonorepoBuilder20211001\Symfony\Component\HttpKernel\Bundle\Bundle;
use MonorepoBuilder20211001\Symplify\ComposerJsonManipulator\DependencyInjection\Extension\ComposerJsonManipulatorExtension;
final class ComposerJsonManipulatorBundle extends \MonorepoBuilder20211001\Symfony\Component\HttpKernel\Bundle\Bundle
{
    protected function createContainerExtension() : ?\MonorepoBuilder20211001\Symfony\Component\DependencyInjection\Extension\ExtensionInterface
    {
        return new \MonorepoBuilder20211001\Symplify\ComposerJsonManipulator\DependencyInjection\Extension\ComposerJsonManipulatorExtension();
    }
}
