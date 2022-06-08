<?php

declare (strict_types=1);
namespace MonorepoBuilder20220608\Symplify\SymplifyKernel\Contract;

use MonorepoBuilder20220608\Psr\Container\ContainerInterface;
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
