<?php

declare (strict_types=1);
namespace MonorepoBuilder20211023\Symplify\ConsoleColorDiff\Bundle;

use MonorepoBuilder20211023\Symfony\Component\HttpKernel\Bundle\Bundle;
use MonorepoBuilder20211023\Symplify\ConsoleColorDiff\DependencyInjection\Extension\ConsoleColorDiffExtension;
final class ConsoleColorDiffBundle extends \MonorepoBuilder20211023\Symfony\Component\HttpKernel\Bundle\Bundle
{
    protected function createContainerExtension() : ?\MonorepoBuilder20211023\Symfony\Component\DependencyInjection\Extension\ExtensionInterface
    {
        return new \MonorepoBuilder20211023\Symplify\ConsoleColorDiff\DependencyInjection\Extension\ConsoleColorDiffExtension();
    }
}
