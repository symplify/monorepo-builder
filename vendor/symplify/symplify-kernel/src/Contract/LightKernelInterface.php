<?php

declare (strict_types=1);
namespace MonorepoBuilderPrefix202304\Symplify\SymplifyKernel\Contract;

use MonorepoBuilderPrefix202304\Psr\Container\ContainerInterface;
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
