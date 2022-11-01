<?php

declare (strict_types=1);
namespace MonorepoBuilder202211\Symplify\SymplifyKernel\Contract\Config;

use MonorepoBuilder202211\Symfony\Component\Config\Loader\LoaderInterface;
use MonorepoBuilder202211\Symfony\Component\DependencyInjection\ContainerBuilder;
interface LoaderFactoryInterface
{
    public function create(ContainerBuilder $containerBuilder, string $currentWorkingDirectory) : LoaderInterface;
}
