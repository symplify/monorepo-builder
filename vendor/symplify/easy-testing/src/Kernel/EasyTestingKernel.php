<?php

declare (strict_types=1);
namespace MonorepoBuilderPrefix202507\Symplify\EasyTesting\Kernel;

use MonorepoBuilderPrefix202507\Psr\Container\ContainerInterface;
use MonorepoBuilderPrefix202507\Symplify\EasyTesting\ValueObject\EasyTestingConfig;
use MonorepoBuilderPrefix202507\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel;
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
