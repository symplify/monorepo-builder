<?php

declare (strict_types=1);
namespace MonorepoBuilder20220124\Symplify\SymplifyKernel\Contract\Config;

use MonorepoBuilder20220124\Symfony\Component\Config\Loader\LoaderInterface;
use MonorepoBuilder20220124\Symfony\Component\DependencyInjection\ContainerBuilder;
interface LoaderFactoryInterface
{
    public function create(\MonorepoBuilder20220124\Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder, string $currentWorkingDirectory) : \MonorepoBuilder20220124\Symfony\Component\Config\Loader\LoaderInterface;
}
