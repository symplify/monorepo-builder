<?php

declare (strict_types=1);
namespace MonorepoBuilder20210707\Symplify\PackageBuilder\Contract\HttpKernel;

use MonorepoBuilder20210707\Symfony\Component\HttpKernel\KernelInterface;
use MonorepoBuilder20210707\Symplify\SmartFileSystem\SmartFileInfo;
interface ExtraConfigAwareKernelInterface extends \MonorepoBuilder20210707\Symfony\Component\HttpKernel\KernelInterface
{
    /**
     * @param string[]|SmartFileInfo[] $configs
     */
    public function setConfigs(array $configs) : void;
}
