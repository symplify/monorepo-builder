<?php

declare (strict_types=1);
namespace MonorepoBuilder202207\Symplify\EasyTesting\Kernel;

use MonorepoBuilder202207\Psr\Container\ContainerInterface;
use MonorepoBuilder202207\Symplify\EasyTesting\ValueObject\EasyTestingConfig;
use MonorepoBuilder202207\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel;
final class EasyTestingKernel extends AbstractSymplifyKernel
{
    /**
     * @param string[] $configFiles
     */
    public function createFromConfigs(array $configFiles) : ContainerInterface
    {
        $configFiles[] = EasyTestingConfig::FILE_PATH;
        return $this->create($configFiles);
    }
}
