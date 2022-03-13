<?php

declare (strict_types=1);
namespace MonorepoBuilder20220313\Symplify\SymplifyKernel\Contract\Config;

use MonorepoBuilder20220313\Symfony\Component\Config\Loader\LoaderInterface;
use MonorepoBuilder20220313\Symfony\Component\DependencyInjection\ContainerBuilder;
interface LoaderFactoryInterface
{
    public function create(\MonorepoBuilder20220313\Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder, string $currentWorkingDirectory) : \MonorepoBuilder20220313\Symfony\Component\Config\Loader\LoaderInterface;
}
