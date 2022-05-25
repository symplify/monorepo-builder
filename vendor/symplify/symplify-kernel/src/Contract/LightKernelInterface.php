<?php

declare (strict_types=1);
namespace MonorepoBuilder20220525\Symplify\SymplifyKernel\Contract;

use MonorepoBuilder20220525\Psr\Container\ContainerInterface;
/**
 * @api
 */
interface LightKernelInterface
{
    /**
     * @param string[] $configFiles
     */
    public function createFromConfigs(array $configFiles) : \MonorepoBuilder20220525\Psr\Container\ContainerInterface;
    public function getContainer() : \MonorepoBuilder20220525\Psr\Container\ContainerInterface;
}
