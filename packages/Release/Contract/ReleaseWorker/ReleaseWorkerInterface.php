<?php

declare (strict_types=1);
namespace MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker;

use MonorepoBuilder20210705\PharIo\Version\Version;
interface ReleaseWorkerInterface
{
    /**
     * 1 line description of what this worker does, in a commanding form! e.g.:
     * - "Add new tag"
     * - "Dump new items to CHANGELOG.md"
     * - "Run coding standards"
     */
    public function getDescription(\MonorepoBuilder20210705\PharIo\Version\Version $version) : string;
    public function work(\MonorepoBuilder20210705\PharIo\Version\Version $version) : void;
}
