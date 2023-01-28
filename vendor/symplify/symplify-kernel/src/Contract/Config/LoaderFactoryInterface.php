<?php

declare (strict_types=1);
namespace MonorepoBuilderPrefix202301\Symplify\SymplifyKernel\Contract\Config;

use MonorepoBuilderPrefix202301\Symfony\Component\Config\Loader\LoaderInterface;
use MonorepoBuilderPrefix202301\Symfony\Component\DependencyInjection\ContainerBuilder;
interface LoaderFactoryInterface
{
    public function create(ContainerBuilder $containerBuilder, string $currentWorkingDirectory) : LoaderInterface;
}
