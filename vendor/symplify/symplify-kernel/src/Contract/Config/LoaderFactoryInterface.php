<?php

declare (strict_types=1);
namespace MonorepoBuilderPrefix202311\Symplify\SymplifyKernel\Contract\Config;

use MonorepoBuilderPrefix202311\Symfony\Component\Config\Loader\LoaderInterface;
use MonorepoBuilderPrefix202311\Symfony\Component\DependencyInjection\ContainerBuilder;
interface LoaderFactoryInterface
{
    public function create(ContainerBuilder $containerBuilder, string $currentWorkingDirectory) : LoaderInterface;
}
