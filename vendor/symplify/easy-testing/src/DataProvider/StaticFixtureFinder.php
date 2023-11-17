<?php

declare (strict_types=1);
namespace MonorepoBuilderPrefix202311\Symplify\EasyTesting\DataProvider;

use Iterator;
use MonorepoBuilderPrefix202311\Nette\Utils\Strings;
use MonorepoBuilderPrefix202311\Symfony\Component\Finder\Finder;
use MonorepoBuilderPrefix202311\Symfony\Component\Finder\SplFileInfo;
use MonorepoBuilderPrefix202311\Symplify\SmartFileSystem\Exception\FileNotFoundException;
use MonorepoBuilderPrefix202311\Symplify\SmartFileSystem\SmartFileInfo;
use MonorepoBuilderPrefix202311\Symplify\SymplifyKernel\Exception\ShouldNotHappenException;
/**
 * @api
 * @see \Symplify\EasyTesting\Tests\DataProvider\StaticFixtureFinder\StaticFixtureFinderTest
 */
final class StaticFixtureFinder
{
    /**
     * @return Iterator<array<int, SmartFileInfo>>
     */
    public static function yieldDirectory(string $directory, string $suffix = '*.php.inc') : Iterator
    {
        $fileInfos = self::findFilesInDirectory($directory, $suffix);
        return self::yieldFileInfos($fileInfos);
    }
    /**
     * @return Iterator<SmartFileInfo>
     */
    public static function yieldDirectoryExclusively(string $directory, string $suffix = '*.php.inc') : Iterator
    {
        $fileInfos = self::findFilesInDirectoryExclusively($directory, $suffix);
        return self::yieldFileInfos($fileInfos);
    }
    /**
     * @return Iterator<string, array<int, SmartFileInfo>>
     */
    public static function yieldDirectoryWithRelativePathname(string $directory, string $suffix = '*.php.inc') : Iterator
    {
        $fileInfos = self::findFilesInDirectory($directory, $suffix);
        return self::yieldFileInfosWithRelativePathname($fileInfos);
    }
    /**
     * @return Iterator<string, array<int, SmartFileInfo>>
     */
    public static function yieldDirectoryExclusivelyWithRelativePathname(string $directory, string $suffix = '*.php.inc') : Iterator
    {
        $fileInfos = self::findFilesInDirectoryExclusively($directory, $suffix);
        return self::yieldFileInfosWithRelativePathname($fileInfos);
    }
    /**
     * @param SplFileInfo[] $fileInfos
     * @return Iterator<array<int, SmartFileInfo>>
     */
    private static function yieldFileInfos(array $fileInfos) : Iterator
    {
        foreach ($fileInfos as $fileInfo) {
            try {
                $smartFileInfo = new SmartFileInfo($fileInfo->getRealPath());
                (yield [$smartFileInfo]);
            } catch (FileNotFoundException $exception) {
            }
        }
    }
    /**
     * @param SplFileInfo[] $fileInfos
     * @return Iterator<string, array<int, SmartFileInfo>>
     */
    private static function yieldFileInfosWithRelativePathname(array $fileInfos) : Iterator
    {
        foreach ($fileInfos as $fileInfo) {
            try {
                $smartFileInfo = new SmartFileInfo($fileInfo->getRealPath());
                (yield $fileInfo->getRelativePathname() => [$smartFileInfo]);
            } catch (FileNotFoundException $exception) {
            }
        }
    }
    /**
     * @return SplFileInfo[]
     */
    private static function findFilesInDirectory(string $directory, string $suffix) : array
    {
        $finder = Finder::create()->in($directory)->files()->name($suffix);
        $fileInfos = \iterator_to_array($finder);
        return \array_values($fileInfos);
    }
    /**
     * @return SplFileInfo[]
     */
    private static function findFilesInDirectoryExclusively(string $directory, string $suffix) : array
    {
        self::ensureNoOtherFileName($directory, $suffix);
        $finder = Finder::create()->in($directory)->files()->name($suffix);
        $fileInfos = \iterator_to_array($finder->getIterator());
        return \array_values($fileInfos);
    }
    private static function ensureNoOtherFileName(string $directory, string $suffix) : void
    {
        $finder = Finder::create()->in($directory)->files()->notName($suffix);
        /** @var SplFileInfo[] $fileInfos */
        $fileInfos = \iterator_to_array($finder->getIterator());
        $relativeFilePaths = [];
        foreach ($fileInfos as $fileInfo) {
            $relativeFilePaths[] = Strings::substring($fileInfo->getRealPath(), \strlen(\getcwd()) + 1);
        }
        if ($relativeFilePaths === []) {
            return;
        }
        throw new ShouldNotHappenException(\sprintf('Files "%s" have invalid suffix, use "%s" suffix instead', \implode('", ', $relativeFilePaths), $suffix));
    }
}
