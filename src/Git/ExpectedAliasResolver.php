<?php

declare (strict_types=1);
namespace MonorepoBuilder20210705\Symplify\MonorepoBuilder\Git;

use MonorepoBuilder20210705\Symfony\Component\Process\Process;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Utils\VersionUtils;
final class ExpectedAliasResolver
{
    /**
     * @var \Symplify\MonorepoBuilder\Utils\VersionUtils
     */
    private $versionUtils;
    public function __construct(\MonorepoBuilder20210705\Symplify\MonorepoBuilder\Utils\VersionUtils $versionUtils)
    {
        $this->versionUtils = $versionUtils;
    }
    public function resolve() : string
    {
        $process = new \MonorepoBuilder20210705\Symfony\Component\Process\Process(['git', 'describe', '--abbrev=0', '--tags']);
        $process->run();
        $output = $process->getOutput();
        return $this->versionUtils->getNextAliasFormat($output);
    }
}
