<?php

declare (strict_types=1);
namespace MonorepoBuilder20220219\Symplify\SymplifyKernel\Contract;

use MonorepoBuilder20220219\Psr\Container\ContainerInterface;
/**
 * @api
 */
interface LightKernelInterface
{
    /**
     * @param string[] $configFiles
     */
    public function createFromConfigs(array $configFiles) : \MonorepoBuilder20220219\Psr\Container\ContainerInterface;
    public function getContainer() : \MonorepoBuilder20220219\Psr\Container\ContainerInterface;
}
