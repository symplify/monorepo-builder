<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Propagate\Command;

use MonorepoBuilderPrefix202311\Symfony\Component\Console\Input\InputInterface;
use MonorepoBuilderPrefix202311\Symfony\Component\Console\Input\InputOption;
use MonorepoBuilderPrefix202311\Symfony\Component\Console\Output\OutputInterface;
use Symplify\MonorepoBuilder\ComposerJsonManipulator\FileSystem\JsonFileManager;
use Symplify\MonorepoBuilder\Exception\MissingComposerJsonException;
use Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;
use Symplify\MonorepoBuilder\Propagate\VersionPropagator;
use Symplify\MonorepoBuilder\ValueObject\Option;
use MonorepoBuilderPrefix202311\Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use MonorepoBuilderPrefix202311\Symplify\SmartFileSystem\SmartFileInfo;
final class PropagateCommand extends AbstractSymplifyCommand
{
    /**
     * @var \Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider
     */
    private $composerJsonProvider;
    /**
     * @var \Symplify\MonorepoBuilder\Propagate\VersionPropagator
     */
    private $versionPropagator;
    /**
     * @var \Symplify\MonorepoBuilder\ComposerJsonManipulator\FileSystem\JsonFileManager
     */
    private $jsonFileManager;
    public function __construct(ComposerJsonProvider $composerJsonProvider, VersionPropagator $versionPropagator, JsonFileManager $jsonFileManager)
    {
        $this->composerJsonProvider = $composerJsonProvider;
        $this->versionPropagator = $versionPropagator;
        $this->jsonFileManager = $jsonFileManager;
        parent::__construct();
    }
    protected function configure() : void
    {
        $this->setName('propagate');
        $this->setDescription('Propagate versions from root "composer.json" to all packages, the opposite of "merge" command');
        $this->addOption(Option::DRY_RUN, null, InputOption::VALUE_NONE, 'Report conflict on missing types');
    }
    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $rootComposerJson = $this->composerJsonProvider->getRootComposerJson();
        $isDryRun = (bool) $input->getOption(Option::DRY_RUN);
        foreach ($this->composerJsonProvider->getPackageComposerJsons() as $packageComposerJson) {
            $originalPackageComposerJson = clone $packageComposerJson;
            $this->versionPropagator->propagate($rootComposerJson, $packageComposerJson);
            if ($originalPackageComposerJson->getJsonArray() === $packageComposerJson->getJsonArray()) {
                continue;
            }
            $packageFileInfo = $packageComposerJson->getFileInfo();
            if (!$packageFileInfo instanceof SmartFileInfo) {
                throw new MissingComposerJsonException();
            }
            if ($isDryRun) {
                $this->symfonyStyle->error('Run "composer propagate" to update package versions');
                return self::FAILURE;
            }
            $this->jsonFileManager->printComposerJsonToFilePath($packageComposerJson, $packageFileInfo->getRealPath());
            $message = \sprintf('"%s" was updated to inherit root composer.json versions', $packageFileInfo->getRelativeFilePathFromCwd());
            $this->symfonyStyle->note($message);
        }
        $this->symfonyStyle->success('Propagation was successful');
        return self::SUCCESS;
    }
}
