<?php

declare (strict_types=1);
namespace MonorepoBuilder20220529\Symplify\VendorPatches\Command;

use MonorepoBuilder20220529\Symfony\Component\Console\Input\InputInterface;
use MonorepoBuilder20220529\Symfony\Component\Console\Output\OutputInterface;
use MonorepoBuilder20220529\Symplify\PackageBuilder\Composer\VendorDirProvider;
use MonorepoBuilder20220529\Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use MonorepoBuilder20220529\Symplify\PackageBuilder\Console\Command\CommandNaming;
use MonorepoBuilder20220529\Symplify\VendorPatches\Composer\ComposerPatchesConfigurationUpdater;
use MonorepoBuilder20220529\Symplify\VendorPatches\Console\GenerateCommandReporter;
use MonorepoBuilder20220529\Symplify\VendorPatches\Differ\PatchDiffer;
use MonorepoBuilder20220529\Symplify\VendorPatches\Finder\OldToNewFilesFinder;
use MonorepoBuilder20220529\Symplify\VendorPatches\PatchFileFactory;
final class GenerateCommand extends \MonorepoBuilder20220529\Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand
{
    /**
     * @var \Symplify\VendorPatches\Finder\OldToNewFilesFinder
     */
    private $oldToNewFilesFinder;
    /**
     * @var \Symplify\VendorPatches\Differ\PatchDiffer
     */
    private $patchDiffer;
    /**
     * @var \Symplify\VendorPatches\Composer\ComposerPatchesConfigurationUpdater
     */
    private $composerPatchesConfigurationUpdater;
    /**
     * @var \Symplify\PackageBuilder\Composer\VendorDirProvider
     */
    private $vendorDirProvider;
    /**
     * @var \Symplify\VendorPatches\PatchFileFactory
     */
    private $patchFileFactory;
    /**
     * @var \Symplify\VendorPatches\Console\GenerateCommandReporter
     */
    private $generateCommandReporter;
    public function __construct(\MonorepoBuilder20220529\Symplify\VendorPatches\Finder\OldToNewFilesFinder $oldToNewFilesFinder, \MonorepoBuilder20220529\Symplify\VendorPatches\Differ\PatchDiffer $patchDiffer, \MonorepoBuilder20220529\Symplify\VendorPatches\Composer\ComposerPatchesConfigurationUpdater $composerPatchesConfigurationUpdater, \MonorepoBuilder20220529\Symplify\PackageBuilder\Composer\VendorDirProvider $vendorDirProvider, \MonorepoBuilder20220529\Symplify\VendorPatches\PatchFileFactory $patchFileFactory, \MonorepoBuilder20220529\Symplify\VendorPatches\Console\GenerateCommandReporter $generateCommandReporter)
    {
        $this->oldToNewFilesFinder = $oldToNewFilesFinder;
        $this->patchDiffer = $patchDiffer;
        $this->composerPatchesConfigurationUpdater = $composerPatchesConfigurationUpdater;
        $this->vendorDirProvider = $vendorDirProvider;
        $this->patchFileFactory = $patchFileFactory;
        $this->generateCommandReporter = $generateCommandReporter;
        parent::__construct();
    }
    protected function configure() : void
    {
        $this->setName(\MonorepoBuilder20220529\Symplify\PackageBuilder\Console\Command\CommandNaming::classToName(self::class));
        $this->setDescription('Generate patches from /vendor directory');
    }
    protected function execute(\MonorepoBuilder20220529\Symfony\Component\Console\Input\InputInterface $input, \MonorepoBuilder20220529\Symfony\Component\Console\Output\OutputInterface $output) : int
    {
        $vendorDirectory = $this->vendorDirProvider->provide();
        $oldAndNewFileInfos = $this->oldToNewFilesFinder->find($vendorDirectory);
        $composerExtraPatches = [];
        $addedPatchFilesByPackageName = [];
        foreach ($oldAndNewFileInfos as $oldAndNewFileInfo) {
            if ($oldAndNewFileInfo->isContentIdentical()) {
                $this->generateCommandReporter->reportIdenticalNewAndOldFile($oldAndNewFileInfo);
                continue;
            }
            // write into patches file
            $patchFileRelativePath = $this->patchFileFactory->createPatchFilePath($oldAndNewFileInfo, $vendorDirectory);
            $composerExtraPatches[$oldAndNewFileInfo->getPackageName()][] = $patchFileRelativePath;
            $patchFileAbsolutePath = \dirname($vendorDirectory) . \DIRECTORY_SEPARATOR . $patchFileRelativePath;
            // dump the patch
            $patchDiff = $this->patchDiffer->diff($oldAndNewFileInfo);
            if (\is_file($patchFileAbsolutePath)) {
                $message = \sprintf('File "%s" was updated', $patchFileRelativePath);
                $this->symfonyStyle->note($message);
            } else {
                $message = \sprintf('File "%s" was created', $patchFileRelativePath);
                $this->symfonyStyle->note($message);
            }
            $this->smartFileSystem->dumpFile($patchFileAbsolutePath, $patchDiff);
            $addedPatchFilesByPackageName[$oldAndNewFileInfo->getPackageName()][] = $patchFileRelativePath;
        }
        $this->composerPatchesConfigurationUpdater->updateComposerJsonAndPrint(\getcwd() . '/composer.json', $composerExtraPatches);
        if ($addedPatchFilesByPackageName !== []) {
            $message = \sprintf('Great! %d new patch files added', \count($addedPatchFilesByPackageName));
            $this->symfonyStyle->success($message);
        } else {
            $this->symfonyStyle->success('No new patches were added');
        }
        return self::SUCCESS;
    }
}
