<?php

declare (strict_types=1);
namespace MonorepoBuilder20211030\Symplify\ConsoleColorDiff\Bundle;

use MonorepoBuilder20211030\Symfony\Component\HttpKernel\Bundle\Bundle;
use MonorepoBuilder20211030\Symplify\ConsoleColorDiff\DependencyInjection\Extension\ConsoleColorDiffExtension;
final class ConsoleColorDiffBundle extends \MonorepoBuilder20211030\Symfony\Component\HttpKernel\Bundle\Bundle
{
    protected function createContainerExtension() : ?\MonorepoBuilder20211030\Symfony\Component\DependencyInjection\Extension\ExtensionInterface
    {
        return new \MonorepoBuilder20211030\Symplify\ConsoleColorDiff\DependencyInjection\Extension\ConsoleColorDiffExtension();
    }
}
