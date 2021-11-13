<?php

declare (strict_types=1);
namespace MonorepoBuilder20211113\Symplify\SymplifyKernel\Contract;

use MonorepoBuilder20211113\Psr\Container\ContainerInterface;
/**
 * @api
 */
interface LightKernelInterface
{
    /**
     * @param string[] $configFiles
     */
    public function createFromConfigs($configFiles) : \MonorepoBuilder20211113\Psr\Container\ContainerInterface;
    public function getContainer() : \MonorepoBuilder20211113\Psr\Container\ContainerInterface;
}
