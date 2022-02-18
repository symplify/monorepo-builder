<?php

declare (strict_types=1);
namespace MonorepoBuilder20220218\Symplify\SymplifyKernel\Contract;

use MonorepoBuilder20220218\Psr\Container\ContainerInterface;
/**
 * @api
 */
interface LightKernelInterface
{
    /**
     * @param string[] $configFiles
     */
    public function createFromConfigs(array $configFiles) : \MonorepoBuilder20220218\Psr\Container\ContainerInterface;
    public function getContainer() : \MonorepoBuilder20220218\Psr\Container\ContainerInterface;
}
