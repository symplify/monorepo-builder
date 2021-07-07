<?php

declare (strict_types=1);
namespace MonorepoBuilder20210707\Symplify\EasyTesting\HttpKernel;

use MonorepoBuilder20210707\Symfony\Component\Config\Loader\LoaderInterface;
use MonorepoBuilder20210707\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel;
final class EasyTestingKernel extends \MonorepoBuilder20210707\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel
{
    public function registerContainerConfiguration(\MonorepoBuilder20210707\Symfony\Component\Config\Loader\LoaderInterface $loader) : void
    {
        $loader->load(__DIR__ . '/../../config/config.php');
    }
}
