<?php

declare (strict_types=1);
namespace MonorepoBuilder20211119\Symplify\EasyTesting\Kernel;

use MonorepoBuilder20211119\Psr\Container\ContainerInterface;
use MonorepoBuilder20211119\Symplify\EasyTesting\ValueObject\EasyTestingConfig;
use MonorepoBuilder20211119\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel;
final class EasyTestingKernel extends \MonorepoBuilder20211119\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel
{
    /**
     * @param string[] $configFiles
     */
    public function createFromConfigs($configFiles) : \MonorepoBuilder20211119\Psr\Container\ContainerInterface
    {
        $configFiles[] = \MonorepoBuilder20211119\Symplify\EasyTesting\ValueObject\EasyTestingConfig::FILE_PATH;
        return $this->create([], [], $configFiles);
    }
}
