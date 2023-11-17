<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Release\Command;

use MonorepoBuilderPrefix202311\Symfony\Component\Console\Input\InputArgument;
use MonorepoBuilderPrefix202311\Symfony\Component\Console\Input\InputInterface;
use MonorepoBuilderPrefix202311\Symfony\Component\Console\Input\InputOption;
use MonorepoBuilderPrefix202311\Symfony\Component\Console\Output\OutputInterface;
use Symplify\MonorepoBuilder\Release\Configuration\StageResolver;
use Symplify\MonorepoBuilder\Release\Configuration\VersionResolver;
use Symplify\MonorepoBuilder\Release\Output\ReleaseWorkerReporter;
use Symplify\MonorepoBuilder\Release\ReleaseWorkerProvider;
use Symplify\MonorepoBuilder\Release\ValueObject\SemVersion;
use Symplify\MonorepoBuilder\Release\ValueObject\Stage;
use Symplify\MonorepoBuilder\Validator\SourcesPresenceValidator;
use Symplify\MonorepoBuilder\ValueObject\File;
use Symplify\MonorepoBuilder\ValueObject\Option;
use MonorepoBuilderPrefix202311\Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
final class ReleaseCommand extends AbstractSymplifyCommand
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
    public function __construct(ReleaseWorkerProvider $releaseWorkerProvider, SourcesPresenceValidator $sourcesPresenceValidator, StageResolver $stageResolver, VersionResolver $versionResolver, ReleaseWorkerReporter $releaseWorkerReporter)
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
        $this->setName('release');
        $this->setDescription('Perform release process with set Release Workers.');
        $description = \sprintf('Release version, in format "<major>.<minor>.<patch>" or "v<major>.<minor>.<patch> or one of keywords: "%s"', \implode('", "', SemVersion::ALL));
        $this->addArgument(Option::VERSION, InputArgument::REQUIRED, $description);
        $this->addOption(Option::DRY_RUN, null, InputOption::VALUE_NONE, 'Do not perform operations, just their preview');
        $this->addOption(Option::STAGE, null, InputOption::VALUE_REQUIRED, 'Name of stage to perform', Stage::MAIN);
    }
    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $this->sourcesPresenceValidator->validateRootComposerJsonName();
        // validation phase
        $stage = $this->stageResolver->resolveFromInput($input);
        $releaseWorkers = $this->releaseWorkerProvider->provideByStage($stage);
        if ($releaseWorkers === []) {
            $errorMessage = \sprintf('There are no release workers registered. Be sure to add them to "%s"', File::CONFIG);
            $this->symfonyStyle->error($errorMessage);
            return self::FAILURE;
        }
        $totalWorkerCount = \count($releaseWorkers);
        $i = 0;
        $isDryRun = (bool) $input->getOption(Option::DRY_RUN);
        $version = $this->versionResolver->resolveVersion($input, $stage);
        foreach ($releaseWorkers as $releaseWorker) {
            $title = \sprintf('%d/%d) ', ++$i, $totalWorkerCount) . $releaseWorker->getDescription($version);
            $this->symfonyStyle->title($title);
            $this->releaseWorkerReporter->printMetadata($releaseWorker);
            if (!$isDryRun) {
                if (\method_exists($releaseWorker, 'shouldConfirm') && $releaseWorker->shouldConfirm()['whenTrue']() && !$this->symfonyStyle->confirm($releaseWorker->shouldConfirm()['message'])) {
                    return self::FAILURE;
                }
                $releaseWorker->work($version);
            }
        }
        if ($isDryRun) {
            $this->symfonyStyle->note('Running in dry mode, nothing is changed');
        } elseif ($stage === Stage::MAIN) {
            $message = \sprintf('Version "%s" is now released!', $version->getVersionString());
            $this->symfonyStyle->success($message);
        } else {
            $finishedMessage = \sprintf('Stage "%s" for version "%s" is now finished!', $stage, $version->getVersionString());
            $this->symfonyStyle->success($finishedMessage);
        }
        return self::SUCCESS;
    }
}
