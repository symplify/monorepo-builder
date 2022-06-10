<?php

declare (strict_types=1);
namespace MonorepoBuilder20220610\Symplify\EasyTesting\Kernel;

use MonorepoBuilder20220610\Psr\Container\ContainerInterface;
use MonorepoBuilder20220610\Symplify\EasyTesting\ValueObject\EasyTestingConfig;
use MonorepoBuilder20220610\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel;
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
