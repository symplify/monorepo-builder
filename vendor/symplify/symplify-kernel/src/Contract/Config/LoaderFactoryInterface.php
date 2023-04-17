<?php

declare (strict_types=1);
namespace MonorepoBuilderPrefix202304\Symplify\SymplifyKernel\Contract\Config;

use MonorepoBuilderPrefix202304\Symfony\Component\Config\Loader\LoaderInterface;
use MonorepoBuilderPrefix202304\Symfony\Component\DependencyInjection\ContainerBuilder;
interface LoaderFactoryInterface
{
    public function create(ContainerBuilder $containerBuilder, string $currentWorkingDirectory) : LoaderInterface;
}
