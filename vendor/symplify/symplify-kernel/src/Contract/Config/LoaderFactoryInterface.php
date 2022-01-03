<?php

declare (strict_types=1);
namespace MonorepoBuilder20220103\Symplify\SymplifyKernel\Contract\Config;

use MonorepoBuilder20220103\Symfony\Component\Config\Loader\LoaderInterface;
use MonorepoBuilder20220103\Symfony\Component\DependencyInjection\ContainerBuilder;
interface LoaderFactoryInterface
{
    public function create(\MonorepoBuilder20220103\Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder, string $currentWorkingDirectory) : \MonorepoBuilder20220103\Symfony\Component\Config\Loader\LoaderInterface;
}
