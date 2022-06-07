<?php

declare (strict_types=1);
namespace MonorepoBuilder20220607\Symplify\SymplifyKernel\Contract\Config;

use MonorepoBuilder20220607\Symfony\Component\Config\Loader\LoaderInterface;
use MonorepoBuilder20220607\Symfony\Component\DependencyInjection\ContainerBuilder;
interface LoaderFactoryInterface
{
    public function create(\MonorepoBuilder20220607\Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder, string $currentWorkingDirectory) : \MonorepoBuilder20220607\Symfony\Component\Config\Loader\LoaderInterface;
}
