<?php

declare (strict_types=1);
namespace MonorepoBuilder202206\Symplify\SymplifyKernel\Contract\Config;

use MonorepoBuilder202206\Symfony\Component\Config\Loader\LoaderInterface;
use MonorepoBuilder202206\Symfony\Component\DependencyInjection\ContainerBuilder;
interface LoaderFactoryInterface
{
    public function create(ContainerBuilder $containerBuilder, string $currentWorkingDirectory) : LoaderInterface;
}
