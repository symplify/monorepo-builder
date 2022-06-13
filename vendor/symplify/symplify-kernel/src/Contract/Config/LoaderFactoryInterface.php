<?php

declare (strict_types=1);
namespace MonorepoBuilder20220613\Symplify\SymplifyKernel\Contract\Config;

use MonorepoBuilder20220613\Symfony\Component\Config\Loader\LoaderInterface;
use MonorepoBuilder20220613\Symfony\Component\DependencyInjection\ContainerBuilder;
interface LoaderFactoryInterface
{
    public function create(ContainerBuilder $containerBuilder, string $currentWorkingDirectory) : LoaderInterface;
}
