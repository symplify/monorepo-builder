<?php

declare (strict_types=1);
namespace MonorepoBuilder20210721\Symplify\ConsoleColorDiff\Bundle;

use MonorepoBuilder20210721\Symfony\Component\HttpKernel\Bundle\Bundle;
use MonorepoBuilder20210721\Symplify\ConsoleColorDiff\DependencyInjection\Extension\ConsoleColorDiffExtension;
final class ConsoleColorDiffBundle extends \MonorepoBuilder20210721\Symfony\Component\HttpKernel\Bundle\Bundle
{
    protected function createContainerExtension() : ?\MonorepoBuilder20210721\Symfony\Component\DependencyInjection\Extension\ExtensionInterface
    {
        return new \MonorepoBuilder20210721\Symplify\ConsoleColorDiff\DependencyInjection\Extension\ConsoleColorDiffExtension();
    }
}
