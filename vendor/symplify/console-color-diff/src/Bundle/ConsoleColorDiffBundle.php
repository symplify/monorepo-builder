<?php

declare (strict_types=1);
namespace MonorepoBuilder20210823\Symplify\ConsoleColorDiff\Bundle;

use MonorepoBuilder20210823\Symfony\Component\HttpKernel\Bundle\Bundle;
use MonorepoBuilder20210823\Symplify\ConsoleColorDiff\DependencyInjection\Extension\ConsoleColorDiffExtension;
final class ConsoleColorDiffBundle extends \MonorepoBuilder20210823\Symfony\Component\HttpKernel\Bundle\Bundle
{
    protected function createContainerExtension() : ?\MonorepoBuilder20210823\Symfony\Component\DependencyInjection\Extension\ExtensionInterface
    {
        return new \MonorepoBuilder20210823\Symplify\ConsoleColorDiff\DependencyInjection\Extension\ConsoleColorDiffExtension();
    }
}
