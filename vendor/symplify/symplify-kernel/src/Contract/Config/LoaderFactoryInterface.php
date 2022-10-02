<?php

declare (strict_types=1);
namespace MonorepoBuilder202210\Symplify\SymplifyKernel\Contract\Config;

use MonorepoBuilder202210\Symfony\Component\Config\Loader\LoaderInterface;
use MonorepoBuilder202210\Symfony\Component\DependencyInjection\ContainerBuilder;
interface LoaderFactoryInterface
{
    public function create(ContainerBuilder $containerBuilder, string $currentWorkingDirectory) : LoaderInterface;
}
