<?php

declare (strict_types=1);
namespace MonorepoBuilder20211223\Symplify\EasyTesting;

use MonorepoBuilder20211223\Nette\Utils\Strings;
use MonorepoBuilder20211223\Symplify\EasyTesting\ValueObject\InputAndExpected;
use MonorepoBuilder20211223\Symplify\EasyTesting\ValueObject\InputFileInfoAndExpected;
use MonorepoBuilder20211223\Symplify\EasyTesting\ValueObject\InputFileInfoAndExpectedFileInfo;
use MonorepoBuilder20211223\Symplify\EasyTesting\ValueObject\SplitLine;
use MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo;
use MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileSystem;
/**
 * @api
 */
final class StaticFixtureSplitter
{
    /**
     * @var string|null
     */
    public static $customTemporaryPath;
    public static function splitFileInfoToInputAndExpected(\MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo $smartFileInfo) : \MonorepoBuilder20211223\Symplify\EasyTesting\ValueObject\InputAndExpected
    {
        $splitLineCount = \count(\MonorepoBuilder20211223\Nette\Utils\Strings::matchAll($smartFileInfo->getContents(), \MonorepoBuilder20211223\Symplify\EasyTesting\ValueObject\SplitLine::SPLIT_LINE_REGEX));
        // if more or less, it could be a test cases for monorepo line in it
        if ($splitLineCount === 1) {
            // input → expected
            [$input, $expected] = \MonorepoBuilder20211223\Nette\Utils\Strings::split($smartFileInfo->getContents(), \MonorepoBuilder20211223\Symplify\EasyTesting\ValueObject\SplitLine::SPLIT_LINE_REGEX);
            $expected = self::retypeExpected($expected);
            return new \MonorepoBuilder20211223\Symplify\EasyTesting\ValueObject\InputAndExpected($input, $expected);
        }
        // no changes
        return new \MonorepoBuilder20211223\Symplify\EasyTesting\ValueObject\InputAndExpected($smartFileInfo->getContents(), $smartFileInfo->getContents());
    }
    public static function splitFileInfoToLocalInputAndExpectedFileInfos(\MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo $smartFileInfo, bool $autoloadTestFixture = \false) : \MonorepoBuilder20211223\Symplify\EasyTesting\ValueObject\InputFileInfoAndExpectedFileInfo
    {
        $inputAndExpected = self::splitFileInfoToInputAndExpected($smartFileInfo);
        $inputFileInfo = self::createTemporaryFileInfo($smartFileInfo, 'input', $inputAndExpected->getInput());
        // some files needs to be autoload to enable reflection
        if ($autoloadTestFixture) {
            require_once $inputFileInfo->getRealPath();
        }
        $expectedFileInfo = self::createTemporaryFileInfo($smartFileInfo, 'expected', $inputAndExpected->getExpected());
        return new \MonorepoBuilder20211223\Symplify\EasyTesting\ValueObject\InputFileInfoAndExpectedFileInfo($inputFileInfo, $expectedFileInfo);
    }
    public static function getTemporaryPath() : string
    {
        if (self::$customTemporaryPath !== null) {
            return self::$customTemporaryPath;
        }
        return \sys_get_temp_dir() . '/_temp_fixture_easy_testing';
    }
    public static function createTemporaryFileInfo(\MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo $fixtureSmartFileInfo, string $prefix, string $fileContent) : \MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo
    {
        $temporaryFilePath = self::createTemporaryPathWithPrefix($fixtureSmartFileInfo, $prefix);
        $dir = \dirname($temporaryFilePath);
        if (!\is_dir($dir)) {
            \mkdir($dir, 0777, \true);
        }
        /** @phpstan-ignore-next-line we don't use SmartFileSystem->dump() for performance reasons */
        \file_put_contents($temporaryFilePath, $fileContent);
        return new \MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo($temporaryFilePath);
    }
    public static function splitFileInfoToLocalInputAndExpected(\MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo $smartFileInfo, bool $autoloadTestFixture = \false) : \MonorepoBuilder20211223\Symplify\EasyTesting\ValueObject\InputFileInfoAndExpected
    {
        $inputAndExpected = self::splitFileInfoToInputAndExpected($smartFileInfo);
        $inputFileInfo = self::createTemporaryFileInfo($smartFileInfo, 'input', $inputAndExpected->getInput());
        // some files needs to be autoload to enable reflection
        if ($autoloadTestFixture) {
            require_once $inputFileInfo->getRealPath();
        }
        return new \MonorepoBuilder20211223\Symplify\EasyTesting\ValueObject\InputFileInfoAndExpected($inputFileInfo, $inputAndExpected->getExpected());
    }
    private static function createTemporaryPathWithPrefix(\MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo $smartFileInfo, string $prefix) : string
    {
        $hash = \MonorepoBuilder20211223\Nette\Utils\Strings::substring(\md5($smartFileInfo->getRealPath()), -20);
        $fileBaseName = $smartFileInfo->getBasename('.inc');
        return self::getTemporaryPath() . \sprintf('/%s_%s_%s', $prefix, $hash, $fileBaseName);
    }
    /**
     * @return mixed
     * @param mixed $expected
     */
    private static function retypeExpected($expected)
    {
        if (!\is_numeric(\trim($expected))) {
            return $expected;
        }
        // value re-type
        if (\strlen((string) (int) $expected) === \strlen(\trim($expected))) {
            return (int) $expected;
        }
        if (\strlen((string) (float) $expected) === \strlen(\trim($expected))) {
            return (float) $expected;
        }
        return $expected;
    }
}
