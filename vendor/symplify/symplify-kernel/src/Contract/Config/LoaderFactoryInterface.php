<?php

declare (strict_types=1);
namespace MonorepoBuilder20220206\Symplify\SymplifyKernel\Contract\Config;

use MonorepoBuilder20220206\Symfony\Component\Config\Loader\LoaderInterface;
use MonorepoBuilder20220206\Symfony\Component\DependencyInjection\ContainerBuilder;
interface LoaderFactoryInterface
{
    public function create(\MonorepoBuilder20220206\Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder, string $currentWorkingDirectory) : \MonorepoBuilder20220206\Symfony\Component\Config\Loader\LoaderInterface;
}
