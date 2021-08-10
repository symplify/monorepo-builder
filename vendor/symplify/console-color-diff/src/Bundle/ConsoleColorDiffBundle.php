<?php

declare (strict_types=1);
namespace MonorepoBuilder20210810\Symplify\ConsoleColorDiff\Bundle;

use MonorepoBuilder20210810\Symfony\Component\HttpKernel\Bundle\Bundle;
use MonorepoBuilder20210810\Symplify\ConsoleColorDiff\DependencyInjection\Extension\ConsoleColorDiffExtension;
final class ConsoleColorDiffBundle extends \MonorepoBuilder20210810\Symfony\Component\HttpKernel\Bundle\Bundle
{
    protected function createContainerExtension() : ?\MonorepoBuilder20210810\Symfony\Component\DependencyInjection\Extension\ExtensionInterface
    {
        return new \MonorepoBuilder20210810\Symplify\ConsoleColorDiff\DependencyInjection\Extension\ConsoleColorDiffExtension();
    }
}
