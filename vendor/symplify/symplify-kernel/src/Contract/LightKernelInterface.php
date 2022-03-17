<?php

declare (strict_types=1);
namespace MonorepoBuilder20220317\Symplify\SymplifyKernel\Contract;

use MonorepoBuilder20220317\Psr\Container\ContainerInterface;
/**
 * @api
 */
interface LightKernelInterface
{
    /**
     * @param string[] $configFiles
     */
    public function createFromConfigs(array $configFiles) : \MonorepoBuilder20220317\Psr\Container\ContainerInterface;
    public function getContainer() : \MonorepoBuilder20220317\Psr\Container\ContainerInterface;
}
