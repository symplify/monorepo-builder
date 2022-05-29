<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge\Command;

use MonorepoBuilder20220529\Symfony\Component\Console\Input\InputInterface;
use MonorepoBuilder20220529\Symfony\Component\Console\Output\OutputInterface;
use MonorepoBuilder20220529\Symplify\ComposerJsonManipulator\ComposerJsonFactory;
use MonorepoBuilder20220529\Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager;
use MonorepoBuilder20220529\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;
use Symplify\MonorepoBuilder\Merge\Application\MergedAndDecoratedComposerJsonFactory;
use Symplify\MonorepoBuilder\Merge\Guard\ConflictingVersionsGuard;
use Symplify\MonorepoBuilder\Validator\SourcesPresenceValidator;
use MonorepoBuilder20220529\Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use MonorepoBuilder20220529\Symplify\PackageBuilder\Console\Command\CommandNaming;
final class MergeCommand extends \MonorepoBuilder20220529\Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand
{
    /**
     * @var \Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider
     */
    private $composerJsonProvider;
    /**
     * @var \Symplify\ComposerJsonManipulator\ComposerJsonFactory
     */
    private $composerJsonFactory;
    /**
     * @var \Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager
     */
    private $jsonFileManager;
    /**
     * @var \Symplify\MonorepoBuilder\Merge\Application\MergedAndDecoratedComposerJsonFactory
     */
    private $mergedAndDecoratedComposerJsonFactory;
    /**
     * @var \Symplify\MonorepoBuilder\Validator\SourcesPresenceValidator
     */
    private $sourcesPresenceValidator;
    /**
     * @var \Symplify\MonorepoBuilder\Merge\Guard\ConflictingVersionsGuard
     */
    private $conflictingVersionsGuard;
    public function __construct(\Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider $composerJsonProvider, \MonorepoBuilder20220529\Symplify\ComposerJsonManipulator\ComposerJsonFactory $composerJsonFactory, \MonorepoBuilder20220529\Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager $jsonFileManager, \Symplify\MonorepoBuilder\Merge\Application\MergedAndDecoratedComposerJsonFactory $mergedAndDecoratedComposerJsonFactory, \Symplify\MonorepoBuilder\Validator\SourcesPresenceValidator $sourcesPresenceValidator, \Symplify\MonorepoBuilder\Merge\Guard\ConflictingVersionsGuard $conflictingVersionsGuard)
    {
        $this->composerJsonProvider = $composerJsonProvider;
        $this->composerJsonFactory = $composerJsonFactory;
        $this->jsonFileManager = $jsonFileManager;
        $this->mergedAndDecoratedComposerJsonFactory = $mergedAndDecoratedComposerJsonFactory;
        $this->sourcesPresenceValidator = $sourcesPresenceValidator;
        $this->conflictingVersionsGuard = $conflictingVersionsGuard;
        parent::__construct();
    }
    protected function configure() : void
    {
        $this->setName(\MonorepoBuilder20220529\Symplify\PackageBuilder\Console\Command\CommandNaming::classToName(self::class));
        $this->setDescription('Merge "composer.json" from all found packages to root one');
    }
    protected function execute(\MonorepoBuilder20220529\Symfony\Component\Console\Input\InputInterface $input, \MonorepoBuilder20220529\Symfony\Component\Console\Output\OutputInterface $output) : int
    {
        $this->sourcesPresenceValidator->validatePackageComposerJsons();
        $this->conflictingVersionsGuard->ensureNoConflictingPackageVersions();
        $rootComposerJsonFilePath = \getcwd() . '/composer.json';
        $rootComposerJson = $this->getRootComposerJson($rootComposerJsonFilePath);
        $packageFileInfos = $this->composerJsonProvider->getPackagesComposerFileInfos();
        $this->mergedAndDecoratedComposerJsonFactory->createFromRootConfigAndPackageFileInfos($rootComposerJson, $packageFileInfos);
        $this->jsonFileManager->printComposerJsonToFilePath($rootComposerJson, $rootComposerJsonFilePath);
        $this->symfonyStyle->success('Root "composer.json" was updated.');
        return self::SUCCESS;
    }
    private function getRootComposerJson(string $rootComposerJsonFilePath) : \MonorepoBuilder20220529\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson
    {
        $rootComposerJson = $this->composerJsonFactory->createFromFilePath($rootComposerJsonFilePath);
        // ignore "provide" section in current root composer.json
        $rootComposerJson->setProvide([]);
        return $rootComposerJson;
    }
}
