<?php

declare (strict_types=1);
namespace MonorepoBuilder20220224\Symplify\SymplifyKernel\Contract\Config;

use MonorepoBuilder20220224\Symfony\Component\Config\Loader\LoaderInterface;
use MonorepoBuilder20220224\Symfony\Component\DependencyInjection\ContainerBuilder;
interface LoaderFactoryInterface
{
    public function create(\MonorepoBuilder20220224\Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder, string $currentWorkingDirectory) : \MonorepoBuilder20220224\Symfony\Component\Config\Loader\LoaderInterface;
}
