<?php

declare (strict_types=1);
namespace MonorepoBuilder20210705\Symplify\MonorepoBuilder\Command;

use MonorepoBuilder20210705\Symfony\Component\Console\Input\InputInterface;
use MonorepoBuilder20210705\Symfony\Component\Console\Output\OutputInterface;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Validator\ConflictingPackageVersionsReporter;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Validator\SourcesPresenceValidator;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\VersionValidator;
use MonorepoBuilder20210705\Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use MonorepoBuilder20210705\Symplify\PackageBuilder\Console\ShellCode;
final class ValidateCommand extends \MonorepoBuilder20210705\Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand
{
    /**
     * @var \Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider
     */
    private $composerJsonProvider;
    /**
     * @var \Symplify\MonorepoBuilder\VersionValidator
     */
    private $versionValidator;
    /**
     * @var \Symplify\MonorepoBuilder\Validator\ConflictingPackageVersionsReporter
     */
    private $conflictingPackageVersionsReporter;
    /**
     * @var \Symplify\MonorepoBuilder\Validator\SourcesPresenceValidator
     */
    private $sourcesPresenceValidator;
    public function __construct(\MonorepoBuilder20210705\Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider $composerJsonProvider, \MonorepoBuilder20210705\Symplify\MonorepoBuilder\VersionValidator $versionValidator, \MonorepoBuilder20210705\Symplify\MonorepoBuilder\Validator\ConflictingPackageVersionsReporter $conflictingPackageVersionsReporter, \MonorepoBuilder20210705\Symplify\MonorepoBuilder\Validator\SourcesPresenceValidator $sourcesPresenceValidator)
    {
        $this->composerJsonProvider = $composerJsonProvider;
        $this->versionValidator = $versionValidator;
        $this->conflictingPackageVersionsReporter = $conflictingPackageVersionsReporter;
        $this->sourcesPresenceValidator = $sourcesPresenceValidator;
        parent::__construct();
    }
    protected function configure() : void
    {
        $this->setDescription('Validates synchronized versions in "composer.json" in all found packages.');
    }
    protected function execute(\MonorepoBuilder20210705\Symfony\Component\Console\Input\InputInterface $input, \MonorepoBuilder20210705\Symfony\Component\Console\Output\OutputInterface $output) : int
    {
        $this->sourcesPresenceValidator->validatePackageComposerJsons();
        $conflictingPackageVersions = $this->versionValidator->findConflictingPackageVersionsInFileInfos($this->composerJsonProvider->getRootAndPackageFileInfos());
        if ($conflictingPackageVersions === []) {
            $this->symfonyStyle->success('All packages "composer.json" files use same package versions.');
            return \MonorepoBuilder20210705\Symplify\PackageBuilder\Console\ShellCode::SUCCESS;
        }
        $this->conflictingPackageVersionsReporter->report($conflictingPackageVersions);
        return \MonorepoBuilder20210705\Symplify\PackageBuilder\Console\ShellCode::ERROR;
    }
}
