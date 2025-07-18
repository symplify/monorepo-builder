<?php

declare(strict_types=1);

namespace Symplify\EasyTesting\PHPUnit\Behavior;

use Symfony\Component\Finder\Finder;
use Symplify\EasyTesting\ValueObject\ExpectedAndOutputFileInfoPair;
use Symplify\SmartFileSystem\Finder\FinderSanitizer;
use Symplify\SmartFileSystem\SmartFileInfo;

/**
 * Use only in "\PHPUnit\Framework\TestCase"
 *
 * Answer here
 *
 * @api
 * @see https://stackoverflow.com/questions/54263109/how-to-assert-2-directories-are-identical-in-phpunit
 */
trait DirectoryAssertableTrait
{
    protected function assertDirectoryEquals(string $expectedDirectory, string $outputDirectory): void
    {
        $expectedFileInfos = $this->findFileInfosInDirectory($expectedDirectory);
        $outputFileInfos = $this->findFileInfosInDirectory($outputDirectory);

        $fileInfosByRelativeFilePath = $this->groupFileInfosByRelativeFilePath(
            $expectedFileInfos,
            $expectedDirectory,
            $outputFileInfos,
            $outputDirectory
        );

        foreach ($fileInfosByRelativeFilePath as $relativeFilePath => $expectedAndOutputFileInfoPair) {
            // output file exists
            $this->assertFileExists($outputDirectory . '/' . $relativeFilePath);

            /** @var ExpectedAndOutputFileInfoPair $expectedAndOutputFileInfoPair */
            if (! $expectedAndOutputFileInfoPair->doesOutputFileExist()) {
                continue;
            }

            // they have the same content
            $this->assertSame(
                $expectedAndOutputFileInfoPair->getExpectedFileContent(),
                $expectedAndOutputFileInfoPair->getOutputFileContent(),
                $relativeFilePath
            );
        }
    }

    /**
     * @return SmartFileInfo[]
     */
    private function findFileInfosInDirectory(string $directory): array
    {
        $firstDirectoryFinder = new Finder();
        $firstDirectoryFinder->files()
            ->in($directory);

        $finderSanitizer = new FinderSanitizer();
        return $finderSanitizer->sanitize($firstDirectoryFinder);
    }

    /**
     * @param SmartFileInfo[] $expectedFileInfos
     * @param SmartFileInfo[] $outputFileInfos
     * @return array<string, ExpectedAndOutputFileInfoPair>
     */
    private function groupFileInfosByRelativeFilePath(
        array $expectedFileInfos,
        string $expectedDirectory,
        array $outputFileInfos,
        string $outputDirectory
    ): array {
        $fileInfosByRelativeFilePath = [];

        foreach ($expectedFileInfos as $expectedFileInfo) {
            $relativeFilePath = $expectedFileInfo->getRelativeFilePathFromDirectory($expectedDirectory);

            // match output file info
            $outputFileInfo = $this->resolveFileInfoByRelativeFilePath(
                $outputFileInfos,
                $outputDirectory,
                $relativeFilePath
            );

            $fileInfosByRelativeFilePath[$relativeFilePath] = new ExpectedAndOutputFileInfoPair(
                $expectedFileInfo,
                $outputFileInfo
            );
        }

        return $fileInfosByRelativeFilePath;
    }

    /**
     * @param SmartFileInfo[] $fileInfos
     */
    private function resolveFileInfoByRelativeFilePath(
        array $fileInfos,
        string $directory,
        string $desiredRelativeFilePath
    ): ?SmartFileInfo {
        foreach ($fileInfos as $fileInfo) {
            $relativeFilePath = $fileInfo->getRelativeFilePathFromDirectory($directory);
            if ($desiredRelativeFilePath !== $relativeFilePath) {
                continue;
            }

            return $fileInfo;
        }

        return null;
    }
}
