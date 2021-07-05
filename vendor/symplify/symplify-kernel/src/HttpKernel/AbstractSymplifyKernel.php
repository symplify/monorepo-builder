<?php

declare (strict_types=1);
namespace MonorepoBuilder20210705\Symplify\SymplifyKernel\HttpKernel;

use MonorepoBuilder20210705\Symfony\Component\Config\Loader\LoaderInterface;
use MonorepoBuilder20210705\Symfony\Component\HttpKernel\Bundle\BundleInterface;
use MonorepoBuilder20210705\Symfony\Component\HttpKernel\Kernel;
use MonorepoBuilder20210705\Symplify\PackageBuilder\Contract\HttpKernel\ExtraConfigAwareKernelInterface;
use MonorepoBuilder20210705\Symplify\SmartFileSystem\SmartFileInfo;
use MonorepoBuilder20210705\Symplify\SymplifyKernel\Bundle\SymplifyKernelBundle;
use MonorepoBuilder20210705\Symplify\SymplifyKernel\Strings\KernelUniqueHasher;
abstract class AbstractSymplifyKernel extends \MonorepoBuilder20210705\Symfony\Component\HttpKernel\Kernel implements \MonorepoBuilder20210705\Symplify\PackageBuilder\Contract\HttpKernel\ExtraConfigAwareKernelInterface
{
    /**
     * @var string[]
     */
    private $configs = [];
    public function getCacheDir() : string
    {
        return \sys_get_temp_dir() . '/' . $this->getUniqueKernelHash();
    }
    public function getLogDir() : string
    {
        return \sys_get_temp_dir() . '/' . $this->getUniqueKernelHash() . '_log';
    }
    /**
     * @return BundleInterface[]
     */
    public function registerBundles() : iterable
    {
        return [new \MonorepoBuilder20210705\Symplify\SymplifyKernel\Bundle\SymplifyKernelBundle()];
    }
    /**
     * @param string[]|SmartFileInfo[] $configs
     */
    public function setConfigs(array $configs) : void
    {
        foreach ($configs as $config) {
            if ($config instanceof \MonorepoBuilder20210705\Symplify\SmartFileSystem\SmartFileInfo) {
                $config = $config->getRealPath();
            }
            $this->configs[] = $config;
        }
    }
    public function registerContainerConfiguration(\MonorepoBuilder20210705\Symfony\Component\Config\Loader\LoaderInterface $loader) : void
    {
        foreach ($this->configs as $config) {
            $loader->load($config);
        }
    }
    private function getUniqueKernelHash() : string
    {
        $kernelUniqueHasher = new \MonorepoBuilder20210705\Symplify\SymplifyKernel\Strings\KernelUniqueHasher();
        return $kernelUniqueHasher->hashKernelClass(static::class);
    }
}
