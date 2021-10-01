<?php

declare (strict_types=1);
namespace MonorepoBuilder20211001\Symplify\PackageBuilder\Contract\HttpKernel;

use MonorepoBuilder20211001\Symfony\Component\HttpKernel\KernelInterface;
use MonorepoBuilder20211001\Symplify\SmartFileSystem\SmartFileInfo;
interface ExtraConfigAwareKernelInterface extends \MonorepoBuilder20211001\Symfony\Component\HttpKernel\KernelInterface
{
    /**
     * @param string[]|SmartFileInfo[] $configs
     */
    public function setConfigs($configs) : void;
}
