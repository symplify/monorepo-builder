<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Release\Command;

use MonorepoBuilder20211223\Symfony\Component\Console\Input\InputArgument;
use MonorepoBuilder20211223\Symfony\Component\Console\Input\InputInterface;
use MonorepoBuilder20211223\Symfony\Component\Console\Input\InputOption;
use MonorepoBuilder20211223\Symfony\Component\Console\Output\OutputInterface;
use Symplify\MonorepoBuilder\Release\Configuration\StageResolver;
use Symplify\MonorepoBuilder\Release\Configuration\VersionResolver;
use Symplify\MonorepoBuilder\Release\Output\ReleaseWorkerReporter;
use Symplify\MonorepoBuilder\Release\ReleaseWorkerProvider;
use Symplify\MonorepoBuilder\Release\ValueObject\SemVersion;
use Symplify\MonorepoBuilder\Release\ValueObject\Stage;
use Symplify\MonorepoBuilder\Validator\SourcesPresenceValidator;
use Symplify\MonorepoBuilder\ValueObject\File;
use Symplify\MonorepoBuilder\ValueObject\Option;
use MonorepoBuilder20211223\Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use MonorepoBuilder20211223\Symplify\PackageBuilder\Console\Command\CommandNaming;
final class ReleaseCommand extends \MonorepoBuilder20211223\Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand
{
    /**
     * @var \Symplify\MonorepoBuilder\Release\ReleaseWorkerProvider
     */
    private $releaseWorkerProvider;
    /**
     * @var \Symplify\MonorepoBuilder\Validator\SourcesPresenceValidator
     */
    private $sourcesPresenceValidator;
    /**
     * @var \Symplify\MonorepoBuilder\Release\Configuration\StageResolver
     */
    private $stageResolver;
    /**
     * @var \Symplify\MonorepoBuilder\Release\Configuration\VersionResolver
     */
    private $versionResolver;
    /**
     * @var \Symplify\MonorepoBuilder\Release\Output\ReleaseWorkerReporter
     */
    private $releaseWorkerReporter;
    public function __construct(\Symplify\MonorepoBuilder\Release\ReleaseWorkerProvider $releaseWorkerProvider, \Symplify\MonorepoBuilder\Validator\SourcesPresenceValidator $sourcesPresenceValidator, \Symplify\MonorepoBuilder\Release\Configuration\StageResolver $stageResolver, \Symplify\MonorepoBuilder\Release\Configuration\VersionResolver $versionResolver, \Symplify\MonorepoBuilder\Release\Output\ReleaseWorkerReporter $releaseWorkerReporter)
    {
        $this->releaseWorkerProvider = $releaseWorkerProvider;
        $this->sourcesPresenceValidator = $sourcesPresenceValidator;
        $this->stageResolver = $stageResolver;
        $this->versionResolver = $versionResolver;
        $this->releaseWorkerReporter = $releaseWorkerReporter;
        parent::__construct();
    }
    protected function configure() : void
    {
        $this->setName(\MonorepoBuilder20211223\Symplify\PackageBuilder\Console\Command\CommandNaming::classToName(self::class));
        $this->setDescription('Perform release process with set Release Workers.');
        $description = \sprintf('Release version, in format "<major>.<minor>.<patch>" or "v<major>.<minor>.<patch> or one of keywords: "%s"', \implode('", "', \Symplify\MonorepoBuilder\Release\ValueObject\SemVersion::ALL));
        $this->addArgument(\Symplify\MonorepoBuilder\ValueObject\Option::VERSION, \MonorepoBuilder20211223\Symfony\Component\Console\Input\InputArgument::REQUIRED, $description);
        $this->addOption(\Symplify\MonorepoBuilder\ValueObject\Option::DRY_RUN, null, \MonorepoBuilder20211223\Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Do not perform operations, just their preview');
        $this->addOption(\Symplify\MonorepoBuilder\ValueObject\Option::STAGE, null, \MonorepoBuilder20211223\Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED, 'Name of stage to perform', \Symplify\MonorepoBuilder\Release\ValueObject\Stage::MAIN);
    }
    protected function execute(\MonorepoBuilder20211223\Symfony\Component\Console\Input\InputInterface $input, \MonorepoBuilder20211223\Symfony\Component\Console\Output\OutputInterface $output) : int
    {
        $this->sourcesPresenceValidator->validateRootComposerJsonName();
        // validation phase
        $stage = $this->stageResolver->resolveFromInput($input);
        $releaseWorkers = $this->releaseWorkerProvider->provideByStage($stage);
        if ($releaseWorkers === []) {
            $errorMessage = \sprintf('There are no release workers registered. Be sure to add them to "%s"', \Symplify\MonorepoBuilder\ValueObject\File::CONFIG);
            $this->symfonyStyle->error($errorMessage);
            return self::FAILURE;
        }
        $totalWorkerCount = \count($releaseWorkers);
        $i = 0;
        $isDryRun = (bool) $input->getOption(\Symplify\MonorepoBuilder\ValueObject\Option::DRY_RUN);
        $version = $this->versionResolver->resolveVersion($input, $stage);
        foreach ($releaseWorkers as $releaseWorker) {
            $title = \sprintf('%d/%d) ', ++$i, $totalWorkerCount) . $releaseWorker->getDescription($version);
            $this->symfonyStyle->title($title);
            $this->releaseWorkerReporter->printMetadata($releaseWorker);
            if (!$isDryRun) {
                $releaseWorker->work($version);
            }
        }
        if ($isDryRun) {
            $this->symfonyStyle->note('Running in dry mode, nothing is changed');
        } elseif ($stage === \Symplify\MonorepoBuilder\Release\ValueObject\Stage::MAIN) {
            $message = \sprintf('Version "%s" is now released!', $version->getVersionString());
            $this->symfonyStyle->success($message);
        } else {
            $finishedMessage = \sprintf('Stage "%s" for version "%s" is now finished!', $stage, $version->getVersionString());
            $this->symfonyStyle->success($finishedMessage);
        }
        return self::SUCCESS;
    }
}
