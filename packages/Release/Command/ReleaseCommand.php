<?php

declare (strict_types=1);
namespace MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\Command;

use MonorepoBuilder20210705\Symfony\Component\Console\Input\InputArgument;
use MonorepoBuilder20210705\Symfony\Component\Console\Input\InputInterface;
use MonorepoBuilder20210705\Symfony\Component\Console\Input\InputOption;
use MonorepoBuilder20210705\Symfony\Component\Console\Output\OutputInterface;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\Configuration\StageResolver;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\Configuration\VersionResolver;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\Output\ReleaseWorkerReporter;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\ReleaseWorkerProvider;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\ValueObject\SemVersion;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\ValueObject\Stage;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Validator\SourcesPresenceValidator;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\ValueObject\File;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\ValueObject\Option;
use MonorepoBuilder20210705\Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use MonorepoBuilder20210705\Symplify\PackageBuilder\Console\ShellCode;
final class ReleaseCommand extends \MonorepoBuilder20210705\Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand
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
    public function __construct(\MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\ReleaseWorkerProvider $releaseWorkerProvider, \MonorepoBuilder20210705\Symplify\MonorepoBuilder\Validator\SourcesPresenceValidator $sourcesPresenceValidator, \MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\Configuration\StageResolver $stageResolver, \MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\Configuration\VersionResolver $versionResolver, \MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\Output\ReleaseWorkerReporter $releaseWorkerReporter)
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
        $this->setDescription('Perform release process with set Release Workers.');
        $description = \sprintf('Release version, in format "<major>.<minor>.<patch>" or "v<major>.<minor>.<patch> or one of keywords: "%s"', \implode('", "', \MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\ValueObject\SemVersion::ALL));
        $this->addArgument(\MonorepoBuilder20210705\Symplify\MonorepoBuilder\ValueObject\Option::VERSION, \MonorepoBuilder20210705\Symfony\Component\Console\Input\InputArgument::REQUIRED, $description);
        $this->addOption(\MonorepoBuilder20210705\Symplify\MonorepoBuilder\ValueObject\Option::DRY_RUN, null, \MonorepoBuilder20210705\Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Do not perform operations, just their preview');
        $this->addOption(\MonorepoBuilder20210705\Symplify\MonorepoBuilder\ValueObject\Option::STAGE, null, \MonorepoBuilder20210705\Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED, 'Name of stage to perform', \MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\ValueObject\Stage::MAIN);
    }
    protected function execute(\MonorepoBuilder20210705\Symfony\Component\Console\Input\InputInterface $input, \MonorepoBuilder20210705\Symfony\Component\Console\Output\OutputInterface $output) : int
    {
        $this->sourcesPresenceValidator->validateRootComposerJsonName();
        // validation phase
        $stage = $this->stageResolver->resolveFromInput($input);
        $activeReleaseWorkers = $this->releaseWorkerProvider->provideByStage($stage);
        if ($activeReleaseWorkers === []) {
            $errorMessage = \sprintf('There are no release workers registered. Be sure to add them to "%s"', \MonorepoBuilder20210705\Symplify\MonorepoBuilder\ValueObject\File::CONFIG);
            $this->symfonyStyle->error($errorMessage);
            return \MonorepoBuilder20210705\Symplify\PackageBuilder\Console\ShellCode::ERROR;
        }
        $totalWorkerCount = \count($activeReleaseWorkers);
        $i = 0;
        $isDryRun = (bool) $input->getOption(\MonorepoBuilder20210705\Symplify\MonorepoBuilder\ValueObject\Option::DRY_RUN);
        $version = $this->versionResolver->resolveVersion($input, $stage);
        foreach ($activeReleaseWorkers as $releaseWorker) {
            $title = \sprintf('%d/%d) ', ++$i, $totalWorkerCount) . $releaseWorker->getDescription($version);
            $this->symfonyStyle->title($title);
            $this->releaseWorkerReporter->printMetadata($releaseWorker);
            if (!$isDryRun) {
                $releaseWorker->work($version);
            }
        }
        if ($isDryRun) {
            $this->symfonyStyle->note('Running in dry mode, nothing is changed');
        } elseif ($stage === \MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\ValueObject\Stage::MAIN) {
            $message = \sprintf('Version "%s" is now released!', $version->getVersionString());
            $this->symfonyStyle->success($message);
        } else {
            $finishedMessage = \sprintf('Stage "%s" for version "%s" is now finished!', $stage, $version->getVersionString());
            $this->symfonyStyle->success($finishedMessage);
        }
        return \MonorepoBuilder20210705\Symplify\PackageBuilder\Console\ShellCode::SUCCESS;
    }
}
