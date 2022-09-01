<?php

declare (strict_types=1);
namespace MonorepoBuilder202209\Symplify\SymplifyKernel\Contract\Config;

use MonorepoBuilder202209\Symfony\Component\Config\Loader\LoaderInterface;
use MonorepoBuilder202209\Symfony\Component\DependencyInjection\ContainerBuilder;
interface LoaderFactoryInterface
{
    public function create(ContainerBuilder $containerBuilder, string $currentWorkingDirectory) : LoaderInterface;
}
