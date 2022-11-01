<?php

declare (strict_types=1);
namespace MonorepoBuilder202211\Symplify\SymplifyKernel\Contract;

use MonorepoBuilder202211\Psr\Container\ContainerInterface;
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
