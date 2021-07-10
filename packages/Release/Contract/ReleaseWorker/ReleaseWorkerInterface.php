<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker;

use PharIo\Version\Version;
interface ReleaseWorkerInterface
{
    /**
     * 1 line description of what this worker does, in a commanding form! e.g.:
     * - "Add new tag"
     * - "Dump new items to CHANGELOG.md"
     * - "Run coding standards"
     * @param \PharIo\Version\Version $version
     */
    public function getDescription($version) : string;
    /**
     * @param \PharIo\Version\Version $version
     */
    public function work($version) : void;
}
