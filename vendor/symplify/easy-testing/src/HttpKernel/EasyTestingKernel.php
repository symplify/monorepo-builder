<?php

declare (strict_types=1);
namespace MonorepoBuilder20210726\Symplify\EasyTesting\HttpKernel;

use MonorepoBuilder20210726\Symfony\Component\Config\Loader\LoaderInterface;
use MonorepoBuilder20210726\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel;
final class EasyTestingKernel extends \MonorepoBuilder20210726\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel
{
    /**
     * @param \Symfony\Component\Config\Loader\LoaderInterface $loader
     */
    public function registerContainerConfiguration($loader) : void
    {
        $loader->load(__DIR__ . '/../../config/config.php');
    }
}
