<?php

declare (strict_types=1);
namespace MonorepoBuilder20220126\Symplify\SymplifyKernel\Contract\Config;

use MonorepoBuilder20220126\Symfony\Component\Config\Loader\LoaderInterface;
use MonorepoBuilder20220126\Symfony\Component\DependencyInjection\ContainerBuilder;
interface LoaderFactoryInterface
{
    public function create(\MonorepoBuilder20220126\Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder, string $currentWorkingDirectory) : \MonorepoBuilder20220126\Symfony\Component\Config\Loader\LoaderInterface;
}
