<?php

declare (strict_types=1);
namespace MonorepoBuilderPrefix202408\Symplify\SymplifyKernel\Contract;

use MonorepoBuilderPrefix202408\Psr\Container\ContainerInterface;
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
