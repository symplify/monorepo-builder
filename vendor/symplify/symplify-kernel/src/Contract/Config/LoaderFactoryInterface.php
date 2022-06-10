<?php

declare (strict_types=1);
namespace MonorepoBuilder20220610\Symplify\SymplifyKernel\Contract\Config;

use MonorepoBuilder20220610\Symfony\Component\Config\Loader\LoaderInterface;
use MonorepoBuilder20220610\Symfony\Component\DependencyInjection\ContainerBuilder;
interface LoaderFactoryInterface
{
    public function create(ContainerBuilder $containerBuilder, string $currentWorkingDirectory) : LoaderInterface;
}
