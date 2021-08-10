<?php

declare (strict_types=1);
namespace MonorepoBuilder20210810\Symplify\ComposerJsonManipulator\Bundle;

use MonorepoBuilder20210810\Symfony\Component\HttpKernel\Bundle\Bundle;
use MonorepoBuilder20210810\Symplify\ComposerJsonManipulator\DependencyInjection\Extension\ComposerJsonManipulatorExtension;
final class ComposerJsonManipulatorBundle extends \MonorepoBuilder20210810\Symfony\Component\HttpKernel\Bundle\Bundle
{
    protected function createContainerExtension() : ?\MonorepoBuilder20210810\Symfony\Component\DependencyInjection\Extension\ExtensionInterface
    {
        return new \MonorepoBuilder20210810\Symplify\ComposerJsonManipulator\DependencyInjection\Extension\ComposerJsonManipulatorExtension();
    }
}
