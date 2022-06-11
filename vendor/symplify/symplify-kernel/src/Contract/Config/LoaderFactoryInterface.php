<?php

declare (strict_types=1);
namespace MonorepoBuilder20220611\Symplify\SymplifyKernel\Contract\Config;

use MonorepoBuilder20220611\Symfony\Component\Config\Loader\LoaderInterface;
use MonorepoBuilder20220611\Symfony\Component\DependencyInjection\ContainerBuilder;
interface LoaderFactoryInterface
{
    public function create(ContainerBuilder $containerBuilder, string $currentWorkingDirectory) : LoaderInterface;
}
