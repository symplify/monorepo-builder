<?php

declare (strict_types=1);
namespace MonorepoBuilder202212\Symplify\SymplifyKernel\Contract\Config;

use MonorepoBuilder202212\Symfony\Component\Config\Loader\LoaderInterface;
use MonorepoBuilder202212\Symfony\Component\DependencyInjection\ContainerBuilder;
interface LoaderFactoryInterface
{
    public function create(ContainerBuilder $containerBuilder, string $currentWorkingDirectory) : LoaderInterface;
}
