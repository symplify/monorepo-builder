<?php

declare (strict_types=1);
namespace MonorepoBuilder20210703\Symplify\MonorepoBuilder\Command;

use MonorepoBuilder20210703\Symfony\Component\Console\Input\InputArgument;
use MonorepoBuilder20210703\Symfony\Component\Console\Input\InputInterface;
use MonorepoBuilder20210703\Symfony\Component\Console\Output\OutputInterface;
use MonorepoBuilder20210703\Symplify\MonorepoBuilder\DependencyUpdater;
use MonorepoBuilder20210703\Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;
use MonorepoBuilder20210703\Symplify\MonorepoBuilder\Validator\SourcesPresenceValidator;
use MonorepoBuilder20210703\Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use MonorepoBuilder20210703\Symplify\PackageBuilder\Console\ShellCode;
use MonorepoBuilder20210703\Symplify\SymplifyKernel\Exception\ShouldNotHappenException;
final class BumpInterdependencyCommand extends \MonorepoBuilder20210703\Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand
{
    /**
     * @var string
     */
    private const VERSION_ARGUMENT = 'version';
    /**
     * @var \Symplify\MonorepoBuilder\DependencyUpdater
     */
    private $dependencyUpdater;
    /**
     * @var \Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider
     */
    private $composerJsonProvider;
    /**
     * @var \Symplify\MonorepoBuilder\Validator\SourcesPresenceValidator
     */
    private $sourcesPresenceValidator;
    public function __construct(\MonorepoBuilder20210703\Symplify\MonorepoBuilder\DependencyUpdater $dependencyUpdater, \MonorepoBuilder20210703\Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider $composerJsonProvider, \MonorepoBuilder20210703\Symplify\MonorepoBuilder\Validator\SourcesPresenceValidator $sourcesPresenceValidator)
    {
        $this->dependencyUpdater = $dependencyUpdater;
        $this->composerJsonProvider = $composerJsonProvider;
        $this->sourcesPresenceValidator = $sourcesPresenceValidator;
        parent::__construct();
    }
    protected function configure() : void
    {
        $this->setDescription('Bump dependency of split packages on each other');
        $this->addArgument(self::VERSION_ARGUMENT, \MonorepoBuilder20210703\Symfony\Component\Console\Input\InputArgument::REQUIRED, 'New version of inter-dependencies, e.g. "^4.4.2"');
    }
    protected function execute(\MonorepoBuilder20210703\Symfony\Component\Console\Input\InputInterface $input, \MonorepoBuilder20210703\Symfony\Component\Console\Output\OutputInterface $output) : int
    {
        $this->sourcesPresenceValidator->validateRootComposerJsonName();
        /** @var string $version */
        $version = $input->getArgument(self::VERSION_ARGUMENT);
        $rootComposerJson = $this->composerJsonProvider->getRootComposerJson();
        // @todo resolve better for only found packages
        // see https://github.com/symplify/symplify/pull/1037/files
        $vendorName = $rootComposerJson->getVendorName();
        if ($vendorName === null) {
            throw new \MonorepoBuilder20210703\Symplify\SymplifyKernel\Exception\ShouldNotHappenException();
        }
        $this->dependencyUpdater->updateFileInfosWithVendorAndVersion($this->composerJsonProvider->getPackagesComposerFileInfos(), $vendorName, $version);
        $successMessage = \sprintf('Inter-dependencies of packages were updated to "%s".', $version);
        $this->symfonyStyle->success($successMessage);
        return \MonorepoBuilder20210703\Symplify\PackageBuilder\Console\ShellCode::SUCCESS;
    }
}
