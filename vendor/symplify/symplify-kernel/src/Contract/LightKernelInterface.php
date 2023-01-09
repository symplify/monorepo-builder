<?php

declare (strict_types=1);
namespace MonorepoBuilderPrefix202301\Symplify\SymplifyKernel\Contract;

use MonorepoBuilderPrefix202301\Psr\Container\ContainerInterface;
/**
 * @api
 */
interface LightKernelInterface
{
    /**
     * @param string[] $configFiles
     */
    public function createFromConfigs(array $configFiles) : ContainerInterface;
    public function getContainer() : ContainerInterface;
}
