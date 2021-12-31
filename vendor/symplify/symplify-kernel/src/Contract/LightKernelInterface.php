<?php

declare (strict_types=1);
namespace MonorepoBuilder20211231\Symplify\SymplifyKernel\Contract;

use MonorepoBuilder20211231\Psr\Container\ContainerInterface;
/**
 * @api
 */
interface LightKernelInterface
{
    /**
     * @param string[] $configFiles
     */
    public function createFromConfigs(array $configFiles) : \MonorepoBuilder20211231\Psr\Container\ContainerInterface;
    public function getContainer() : \MonorepoBuilder20211231\Psr\Container\ContainerInterface;
}
