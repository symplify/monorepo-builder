<?php

declare (strict_types=1);
namespace MonorepoBuilder20220529\Symplify\VendorPatches\Kernel;

use MonorepoBuilder20220529\Psr\Container\ContainerInterface;
use MonorepoBuilder20220529\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonManipulatorConfig;
use MonorepoBuilder20220529\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel;
final class VendorPatchesKernel extends \MonorepoBuilder20220529\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel
{
    /**
     * @param string[] $configFiles
     */
    public function createFromConfigs(array $configFiles) : \MonorepoBuilder20220529\Psr\Container\ContainerInterface
    {
        $configFiles[] = __DIR__ . '/../../config/config.php';
        $configFiles[] = \MonorepoBuilder20220529\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonManipulatorConfig::FILE_PATH;
        return $this->create($configFiles);
    }
}
