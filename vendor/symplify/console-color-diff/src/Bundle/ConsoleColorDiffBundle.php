<?php

declare (strict_types=1);
namespace MonorepoBuilder20210918\Symplify\ConsoleColorDiff\Bundle;

use MonorepoBuilder20210918\Symfony\Component\HttpKernel\Bundle\Bundle;
use MonorepoBuilder20210918\Symplify\ConsoleColorDiff\DependencyInjection\Extension\ConsoleColorDiffExtension;
final class ConsoleColorDiffBundle extends \MonorepoBuilder20210918\Symfony\Component\HttpKernel\Bundle\Bundle
{
    protected function createContainerExtension() : ?\MonorepoBuilder20210918\Symfony\Component\DependencyInjection\Extension\ExtensionInterface
    {
        return new \MonorepoBuilder20210918\Symplify\ConsoleColorDiff\DependencyInjection\Extension\ConsoleColorDiffExtension();
    }
}
