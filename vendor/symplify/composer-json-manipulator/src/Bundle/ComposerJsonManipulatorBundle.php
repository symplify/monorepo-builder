<?php

declare (strict_types=1);
namespace MonorepoBuilder20210722\Symplify\ComposerJsonManipulator\Bundle;

use MonorepoBuilder20210722\Symfony\Component\HttpKernel\Bundle\Bundle;
use MonorepoBuilder20210722\Symplify\ComposerJsonManipulator\DependencyInjection\Extension\ComposerJsonManipulatorExtension;
final class ComposerJsonManipulatorBundle extends \MonorepoBuilder20210722\Symfony\Component\HttpKernel\Bundle\Bundle
{
    protected function createContainerExtension() : ?\MonorepoBuilder20210722\Symfony\Component\DependencyInjection\Extension\ExtensionInterface
    {
        return new \MonorepoBuilder20210722\Symplify\ComposerJsonManipulator\DependencyInjection\Extension\ComposerJsonManipulatorExtension();
    }
}
