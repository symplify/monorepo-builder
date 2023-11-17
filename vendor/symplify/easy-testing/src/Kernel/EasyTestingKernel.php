<?php

declare (strict_types=1);
namespace MonorepoBuilderPrefix202311\Symplify\EasyTesting\Kernel;

use MonorepoBuilderPrefix202311\Psr\Container\ContainerInterface;
use MonorepoBuilderPrefix202311\Symplify\EasyTesting\ValueObject\EasyTestingConfig;
use MonorepoBuilderPrefix202311\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel;
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
