<?php

declare (strict_types=1);
namespace MonorepoBuilder20211227\Symplify\SymplifyKernel\Contract;

use MonorepoBuilder20211227\Psr\Container\ContainerInterface;
/**
 * @api
 */
interface LightKernelInterface
{
    /**
     * @param string[] $configFiles
     */
    public function createFromConfigs(array $configFiles) : \MonorepoBuilder20211227\Psr\Container\ContainerInterface;
    public function getContainer() : \MonorepoBuilder20211227\Psr\Container\ContainerInterface;
}
