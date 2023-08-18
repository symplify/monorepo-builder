<?php

declare (strict_types=1);
namespace MonorepoBuilderPrefix202308\Symplify\SymplifyKernel\Contract\Config;

use MonorepoBuilderPrefix202308\Symfony\Component\Config\Loader\LoaderInterface;
use MonorepoBuilderPrefix202308\Symfony\Component\DependencyInjection\ContainerBuilder;
interface LoaderFactoryInterface
{
    public function create(ContainerBuilder $containerBuilder, string $currentWorkingDirectory) : LoaderInterface;
}
