<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Propagate\Command;

use MonorepoBuilder20211223\Symfony\Component\Console\Input\InputInterface;
use MonorepoBuilder20211223\Symfony\Component\Console\Output\OutputInterface;
use MonorepoBuilder20211223\Symplify\Astral\Exception\ShouldNotHappenException;
use MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager;
use Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;
use Symplify\MonorepoBuilder\Propagate\VersionPropagator;
use MonorepoBuilder20211223\Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use MonorepoBuilder20211223\Symplify\PackageBuilder\Console\Command\CommandNaming;
use MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo;
final class PropagateCommand extends \MonorepoBuilder20211223\Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand
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
     * @var \Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager
     */
    private $jsonFileManager;
    public function __construct(\Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider $composerJsonProvider, \Symplify\MonorepoBuilder\Propagate\VersionPropagator $versionPropagator, \MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager $jsonFileManager)
    {
        $this->composerJsonProvider = $composerJsonProvider;
        $this->versionPropagator = $versionPropagator;
        $this->jsonFileManager = $jsonFileManager;
        parent::__construct();
    }
    protected function configure() : void
    {
        $this->setName(\MonorepoBuilder20211223\Symplify\PackageBuilder\Console\Command\CommandNaming::classToName(self::class));
        $this->setDescription('Propagate versions from root "composer.json" to all packages, the opposite of "merge" command');
    }
    protected function execute(\MonorepoBuilder20211223\Symfony\Component\Console\Input\InputInterface $input, \MonorepoBuilder20211223\Symfony\Component\Console\Output\OutputInterface $output) : int
    {
        $rootComposerJson = $this->composerJsonProvider->getRootComposerJson();
        foreach ($this->composerJsonProvider->getPackageComposerJsons() as $packageComposerJson) {
            $originalPackageComposerJson = clone $packageComposerJson;
            $this->versionPropagator->propagate($rootComposerJson, $packageComposerJson);
            if ($originalPackageComposerJson->getJsonArray() === $packageComposerJson->getJsonArray()) {
                continue;
            }
            $packageFileInfo = $packageComposerJson->getFileInfo();
            if (!$packageFileInfo instanceof \MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo) {
                throw new \MonorepoBuilder20211223\Symplify\Astral\Exception\ShouldNotHappenException();
            }
            $this->jsonFileManager->printComposerJsonToFilePath($packageComposerJson, $packageFileInfo->getRealPath());
            $message = \sprintf('"%s" was updated to inherit root composer.json versions', $packageFileInfo->getRelativeFilePathFromCwd());
            $this->symfonyStyle->note($message);
        }
        $this->symfonyStyle->success('Propagation was successful');
        return self::SUCCESS;
    }
}
