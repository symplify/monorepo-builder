<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker;

use MonorepoBuilder20210707\PharIo\Version\Version;
interface ReleaseWorkerInterface
{
    /**
     * 1 line description of what this worker does, in a commanding form! e.g.:
     * - "Add new tag"
     * - "Dump new items to CHANGELOG.md"
     * - "Run coding standards"
     */
    public function getDescription(\MonorepoBuilder20210707\PharIo\Version\Version $version) : string;
    public function work(\MonorepoBuilder20210707\PharIo\Version\Version $version) : void;
}
