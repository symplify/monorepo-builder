<?php

declare(strict_types=1);

namespace Symplify\EasyTesting;

use Nette\Utils\Strings;
use Symplify\EasyTesting\ValueObject\InputAndExpected;
use Symplify\EasyTesting\ValueObject\InputFileInfoAndExpected;
use Symplify\EasyTesting\ValueObject\InputFileInfoAndExpectedFileInfo;
use Symplify\EasyTesting\ValueObject\SplitLine;
use Symplify\SmartFileSystem\SmartFileInfo;
use Symplify\SmartFileSystem\SmartFileSystem;

/**
 * @api
 */
final class StaticFixtureSplitter
{
    public static ?string $customTemporaryPath = null;

    public static function splitFileInfoToInputAndExpected(SmartFileInfo $smartFileInfo): InputAndExpected
    {
        $splitLineCount = count(Strings::matchAll($smartFileInfo->getContents(), SplitLine::SPLIT_LINE_REGEX));

        // if more or less, it could be a test cases for monorepo line in it
        if ($splitLineCount === 1) {
            // input → expected
            [$input, $expected] = Strings::split($smartFileInfo->getContents(), SplitLine::SPLIT_LINE_REGEX);

            $expected = self::retypeExpected($expected);

            return new InputAndExpected($input, $expected);
        }

        // no changes
        return new InputAndExpected($smartFileInfo->getContents(), $smartFileInfo->getContents());
    }

    public static function splitFileInfoToLocalInputAndExpectedFileInfos(
        SmartFileInfo $smartFileInfo,
        bool $autoloadTestFixture = false,
        bool $preserveDirStructure = false
    ): InputFileInfoAndExpectedFileInfo {
        $inputAndExpected = self::splitFileInfoToInputAndExpected($smartFileInfo);

        $prefix = '';
        if ($preserveDirStructure) {
            $dir = explode('Fixture', $smartFileInfo->getRealPath(), 2);
            $prefix = isset($dir[1]) ? dirname($dir[1]) . '/' : '';
            $prefix = ltrim($prefix, '/\\');
        }

        $inputFileInfo = self::createTemporaryFileInfo(
            $smartFileInfo,
            $prefix . 'input',
            $inputAndExpected->getInput()
        );

        // some files needs to be autoload to enable reflection
        if ($autoloadTestFixture) {
            require_once $inputFileInfo->getRealPath();
        }

        $expectedFileInfo = self::createTemporaryFileInfo(
            $smartFileInfo,
            $prefix . 'expected',
            $inputAndExpected->getExpected()
        );

        return new InputFileInfoAndExpectedFileInfo($inputFileInfo, $expectedFileInfo);
    }

    public static function getTemporaryPath(): string
    {
        if (self::$customTemporaryPath !== null) {
            return self::$customTemporaryPath;
        }

        return sys_get_temp_dir() . '/_temp_fixture_easy_testing';
    }

    public static function createTemporaryFileInfo(
        SmartFileInfo $fixtureSmartFileInfo,
        string $prefix,
        string $fileContent
    ): SmartFileInfo {
        $temporaryFilePath = self::createTemporaryPathWithPrefix($fixtureSmartFileInfo, $prefix);

        $dir = dirname($temporaryFilePath);
        if (! is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        file_put_contents($temporaryFilePath, $fileContent);

        return new SmartFileInfo($temporaryFilePath);
    }

    public static function splitFileInfoToLocalInputAndExpected(
        SmartFileInfo $smartFileInfo,
        bool $autoloadTestFixture = false
    ): InputFileInfoAndExpected {
        $inputAndExpected = self::splitFileInfoToInputAndExpected($smartFileInfo);

        $inputFileInfo = self::createTemporaryFileInfo($smartFileInfo, 'input', $inputAndExpected->getInput());

        // some files needs to be autoload to enable reflection
        if ($autoloadTestFixture) {
            require_once $inputFileInfo->getRealPath();
        }

        return new InputFileInfoAndExpected($inputFileInfo, $inputAndExpected->getExpected());
    }

    private static function createTemporaryPathWithPrefix(SmartFileInfo $smartFileInfo, string $prefix): string
    {
        $hash = Strings::substring(md5($smartFileInfo->getRealPath()), -20);

        $fileBasename = $smartFileInfo->getBasename('.inc');

        return self::getTemporaryPath() . sprintf('/%s_%s_%s', $prefix, $hash, $fileBasename);
    }

    private static function retypeExpected(mixed $expected): mixed
    {
        if (! is_numeric(trim((string) $expected))) {
            return $expected;
        }

        // value re-type
        if (strlen((string) (int) $expected) === strlen(trim((string) $expected))) {
            return (int) $expected;
        }

        if (strlen((string) (float) $expected) === strlen(trim((string) $expected))) {
            return (float) $expected;
        }

        return $expected;
    }
}
