<?php

declare (strict_types=1);
namespace MonorepoBuilder20210805\Symplify\PackageBuilder\Contract\HttpKernel;

use MonorepoBuilder20210805\Symfony\Component\HttpKernel\KernelInterface;
use MonorepoBuilder20210805\Symplify\SmartFileSystem\SmartFileInfo;
interface ExtraConfigAwareKernelInterface extends \MonorepoBuilder20210805\Symfony\Component\HttpKernel\KernelInterface
{
    /**
     * @param string[]|SmartFileInfo[] $configs
     */
    public function setConfigs($configs) : void;
}
