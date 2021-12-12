<?php

declare (strict_types=1);
namespace MonorepoBuilder20211212\Symplify\SymplifyKernel\Contract;

use MonorepoBuilder20211212\Psr\Container\ContainerInterface;
/**
 * @api
 */
interface LightKernelInterface
{
    /**
     * @param string[] $configFiles
     */
    public function createFromConfigs(array $configFiles) : \MonorepoBuilder20211212\Psr\Container\ContainerInterface;
    public function getContainer() : \MonorepoBuilder20211212\Psr\Container\ContainerInterface;
}
