<?php

declare (strict_types=1);
namespace MonorepoBuilder20211223\Symplify\EasyTesting\DataProvider;

use MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo;
use MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileSystem;
/**
 * @api
 */
final class StaticFixtureUpdater
{
    /**
     * @param \Symplify\SmartFileSystem\SmartFileInfo|string $originalFileInfo
     */
    public static function updateFixtureContent($originalFileInfo, string $changedContent, \MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo $fixtureFileInfo) : void
    {
        if (!\getenv('UPDATE_TESTS') && !\getenv('UT')) {
            return;
        }
        $newOriginalContent = self::resolveNewFixtureContent($originalFileInfo, $changedContent);
        self::getSmartFileSystem()->dumpFile($fixtureFileInfo->getRealPath(), $newOriginalContent);
    }
    public static function updateExpectedFixtureContent(string $newOriginalContent, \MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo $expectedFixtureFileInfo) : void
    {
        if (!\getenv('UPDATE_TESTS') && !\getenv('UT')) {
            return;
        }
        self::getSmartFileSystem()->dumpFile($expectedFixtureFileInfo->getRealPath(), $newOriginalContent);
    }
    private static function getSmartFileSystem() : \MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileSystem
    {
        return new \MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileSystem();
    }
    /**
     * @param \Symplify\SmartFileSystem\SmartFileInfo|string $originalFileInfo
     */
    private static function resolveNewFixtureContent($originalFileInfo, string $changedContent) : string
    {
        if ($originalFileInfo instanceof \MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo) {
            $originalContent = $originalFileInfo->getContents();
        } else {
            $originalContent = $originalFileInfo;
        }
        if ($originalContent === $changedContent) {
            return $originalContent;
        }
        return $originalContent . '-----' . \PHP_EOL . $changedContent;
    }
}
