<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Command;

use MonorepoBuilder20211223\Symfony\Component\Console\Input\InputInterface;
use MonorepoBuilder20211223\Symfony\Component\Console\Output\OutputInterface;
use Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;
use Symplify\MonorepoBuilder\Validator\ConflictingPackageVersionsReporter;
use Symplify\MonorepoBuilder\Validator\SourcesPresenceValidator;
use Symplify\MonorepoBuilder\VersionValidator;
use MonorepoBuilder20211223\Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use MonorepoBuilder20211223\Symplify\PackageBuilder\Console\Command\CommandNaming;
final class ValidateCommand extends \MonorepoBuilder20211223\Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand
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
    public function __construct(\Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider $composerJsonProvider, \Symplify\MonorepoBuilder\VersionValidator $versionValidator, \Symplify\MonorepoBuilder\Validator\ConflictingPackageVersionsReporter $conflictingPackageVersionsReporter, \Symplify\MonorepoBuilder\Validator\SourcesPresenceValidator $sourcesPresenceValidator)
    {
        $this->composerJsonProvider = $composerJsonProvider;
        $this->versionValidator = $versionValidator;
        $this->conflictingPackageVersionsReporter = $conflictingPackageVersionsReporter;
        $this->sourcesPresenceValidator = $sourcesPresenceValidator;
        parent::__construct();
    }
    protected function configure() : void
    {
        $this->setName(\MonorepoBuilder20211223\Symplify\PackageBuilder\Console\Command\CommandNaming::classToName(self::class));
        $this->setDescription('Validates synchronized versions in "composer.json" in all found packages.');
    }
    protected function execute(\MonorepoBuilder20211223\Symfony\Component\Console\Input\InputInterface $input, \MonorepoBuilder20211223\Symfony\Component\Console\Output\OutputInterface $output) : int
    {
        $this->sourcesPresenceValidator->validatePackageComposerJsons();
        $conflictingPackageVersions = $this->versionValidator->findConflictingPackageVersionsInFileInfos($this->composerJsonProvider->getRootAndPackageFileInfos());
        if ($conflictingPackageVersions === []) {
            $this->symfonyStyle->success('All packages "composer.json" files use same package versions.');
            return self::SUCCESS;
        }
        $this->conflictingPackageVersionsReporter->report($conflictingPackageVersions);
        return self::FAILURE;
    }
}
