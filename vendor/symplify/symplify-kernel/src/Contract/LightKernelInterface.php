<?php

declare (strict_types=1);
namespace MonorepoBuilder202212\Symplify\SymplifyKernel\Contract;

use MonorepoBuilder202212\Psr\Container\ContainerInterface;
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
