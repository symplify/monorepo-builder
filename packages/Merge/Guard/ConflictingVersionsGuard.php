<?php

declare (strict_types=1);
namespace MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\Guard;

use MonorepoBuilder20210705\Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Validator\ConflictingPackageVersionsReporter;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\VersionValidator;
use MonorepoBuilder20210705\Symplify\SymplifyKernel\Exception\ShouldNotHappenException;
final class ConflictingVersionsGuard
{
    /**
     * @var \Symplify\MonorepoBuilder\VersionValidator
     */
    private $versionValidator;
    /**
     * @var \Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider
     */
    private $composerJsonProvider;
    /**
     * @var \Symplify\MonorepoBuilder\Validator\ConflictingPackageVersionsReporter
     */
    private $conflictingPackageVersionsReporter;
    public function __construct(\MonorepoBuilder20210705\Symplify\MonorepoBuilder\VersionValidator $versionValidator, \MonorepoBuilder20210705\Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider $composerJsonProvider, \MonorepoBuilder20210705\Symplify\MonorepoBuilder\Validator\ConflictingPackageVersionsReporter $conflictingPackageVersionsReporter)
    {
        $this->versionValidator = $versionValidator;
        $this->composerJsonProvider = $composerJsonProvider;
        $this->conflictingPackageVersionsReporter = $conflictingPackageVersionsReporter;
    }
    public function ensureNoConflictingPackageVersions() : void
    {
        $conflictingPackageVersions = $this->versionValidator->findConflictingPackageVersionsInFileInfos($this->composerJsonProvider->getPackagesComposerFileInfos());
        if ($conflictingPackageVersions === []) {
            return;
        }
        $this->conflictingPackageVersionsReporter->report($conflictingPackageVersions);
        throw new \MonorepoBuilder20210705\Symplify\SymplifyKernel\Exception\ShouldNotHappenException('Fix conflicting package version first');
    }
}
