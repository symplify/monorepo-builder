<?php

declare (strict_types=1);
namespace MonorepoBuilder20211021\Symplify\EasyTesting\HttpKernel;

use MonorepoBuilder20211021\Symfony\Component\Config\Loader\LoaderInterface;
use MonorepoBuilder20211021\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel;
final class EasyTestingKernel extends \MonorepoBuilder20211021\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel
{
    /**
     * @param \Symfony\Component\Config\Loader\LoaderInterface $loader
     */
    public function registerContainerConfiguration($loader) : void
    {
        $loader->load(__DIR__ . '/../../config/config.php');
    }
}
