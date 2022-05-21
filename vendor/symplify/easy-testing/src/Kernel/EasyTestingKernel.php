<?php

declare (strict_types=1);
namespace MonorepoBuilder20220521\Symplify\EasyTesting\Kernel;

use MonorepoBuilder20220521\Psr\Container\ContainerInterface;
use MonorepoBuilder20220521\Symplify\EasyTesting\ValueObject\EasyTestingConfig;
use MonorepoBuilder20220521\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel;
final class EasyTestingKernel extends \MonorepoBuilder20220521\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel
{
    /**
     * @param string[] $configFiles
     */
    public function createFromConfigs(array $configFiles) : \MonorepoBuilder20220521\Psr\Container\ContainerInterface
    {
        $configFiles[] = \MonorepoBuilder20220521\Symplify\EasyTesting\ValueObject\EasyTestingConfig::FILE_PATH;
        return $this->create($configFiles);
    }
}
