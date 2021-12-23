<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge\Guard;

use Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;
use Symplify\MonorepoBuilder\Validator\ConflictingPackageVersionsReporter;
use Symplify\MonorepoBuilder\VersionValidator;
use MonorepoBuilder20211223\Symplify\SymplifyKernel\Exception\ShouldNotHappenException;
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
    public function __construct(\Symplify\MonorepoBuilder\VersionValidator $versionValidator, \Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider $composerJsonProvider, \Symplify\MonorepoBuilder\Validator\ConflictingPackageVersionsReporter $conflictingPackageVersionsReporter)
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
        throw new \MonorepoBuilder20211223\Symplify\SymplifyKernel\Exception\ShouldNotHappenException('Fix conflicting package version first');
    }
}
