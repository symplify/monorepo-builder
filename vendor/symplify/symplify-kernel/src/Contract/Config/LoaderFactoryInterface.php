<?php

declare (strict_types=1);
namespace MonorepoBuilder20220317\Symplify\SymplifyKernel\Contract\Config;

use MonorepoBuilder20220317\Symfony\Component\Config\Loader\LoaderInterface;
use MonorepoBuilder20220317\Symfony\Component\DependencyInjection\ContainerBuilder;
interface LoaderFactoryInterface
{
    public function create(\MonorepoBuilder20220317\Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder, string $currentWorkingDirectory) : \MonorepoBuilder20220317\Symfony\Component\Config\Loader\LoaderInterface;
}
