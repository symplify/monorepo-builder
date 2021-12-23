<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Validator;

use MonorepoBuilder20211223\Symfony\Component\Console\Style\SymfonyStyle;
use MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo;
final class ConflictingPackageVersionsReporter
{
    /**
     * @var \Symfony\Component\Console\Style\SymfonyStyle
     */
    private $symfonyStyle;
    public function __construct(\MonorepoBuilder20211223\Symfony\Component\Console\Style\SymfonyStyle $symfonyStyle)
    {
        $this->symfonyStyle = $symfonyStyle;
    }
    /**
     * @param array<string, array<string, string>> $conflictingPackages
     */
    public function report(array $conflictingPackages) : void
    {
        foreach ($conflictingPackages as $packageName => $filesToVersions) {
            $message = \sprintf('Package "%s" has incompatible version', $packageName);
            $this->symfonyStyle->title($message);
            $tableRows = $this->createTableRows($filesToVersions);
            $this->symfonyStyle->table(['File', 'Version'], $tableRows);
        }
        $this->symfonyStyle->error('Found conflicting package versions, fix them first.');
    }
    /**
     * @param array<string, string> $filesToVersions
     * @return array<int, mixed[]>
     */
    private function createTableRows(array $filesToVersions) : array
    {
        $tableRows = [];
        foreach ($filesToVersions as $file => $version) {
            $fileInfo = new \MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo($file);
            $tableRows[] = [$fileInfo->getRelativeFilePathFromCwd(), $version];
        }
        return $tableRows;
    }
}
