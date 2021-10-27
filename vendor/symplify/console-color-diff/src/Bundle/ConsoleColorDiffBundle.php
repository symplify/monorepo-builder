<?php

declare (strict_types=1);
namespace MonorepoBuilder20211027\Symplify\ConsoleColorDiff\Bundle;

use MonorepoBuilder20211027\Symfony\Component\HttpKernel\Bundle\Bundle;
use MonorepoBuilder20211027\Symplify\ConsoleColorDiff\DependencyInjection\Extension\ConsoleColorDiffExtension;
final class ConsoleColorDiffBundle extends \MonorepoBuilder20211027\Symfony\Component\HttpKernel\Bundle\Bundle
{
    protected function createContainerExtension() : ?\MonorepoBuilder20211027\Symfony\Component\DependencyInjection\Extension\ExtensionInterface
    {
        return new \MonorepoBuilder20211027\Symplify\ConsoleColorDiff\DependencyInjection\Extension\ConsoleColorDiffExtension();
    }
}
