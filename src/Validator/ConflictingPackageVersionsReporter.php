<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Validator;

use MonorepoBuilderPrefix202311\Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\MonorepoBuilder\Utils\RelativeFilePathHelper;
final class ConflictingPackageVersionsReporter
{
    /**
     * @var \Symfony\Component\Console\Style\SymfonyStyle
     */
    private $symfonyStyle;
    public function __construct(SymfonyStyle $symfonyStyle)
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
            $tableRows[] = [RelativeFilePathHelper::resolveFromCwd($file), $version];
        }
        return $tableRows;
    }
}
