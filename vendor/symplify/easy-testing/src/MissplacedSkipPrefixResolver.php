<?php

declare (strict_types=1);
namespace MonorepoBuilder20220527\Symplify\EasyTesting;

use MonorepoBuilder20220527\Nette\Utils\Strings;
use MonorepoBuilder20220527\Symplify\EasyTesting\ValueObject\IncorrectAndMissingSkips;
use MonorepoBuilder20220527\Symplify\EasyTesting\ValueObject\Prefix;
use MonorepoBuilder20220527\Symplify\EasyTesting\ValueObject\SplitLine;
use MonorepoBuilder20220527\Symplify\SmartFileSystem\SmartFileInfo;
/**
 * @see \Symplify\EasyTesting\Tests\MissingSkipPrefixResolver\MissingSkipPrefixResolverTest
 */
final class MissplacedSkipPrefixResolver
{
    /**
     * @param SmartFileInfo[] $fixtureFileInfos
     */
    public function resolve(array $fixtureFileInfos) : \MonorepoBuilder20220527\Symplify\EasyTesting\ValueObject\IncorrectAndMissingSkips
    {
        $incorrectSkips = [];
        $missingSkips = [];
        foreach ($fixtureFileInfos as $fixtureFileInfo) {
            $hasNameSkipStart = $this->hasNameSkipStart($fixtureFileInfo);
            $fileContents = $fixtureFileInfo->getContents();
            $hasSplitLine = (bool) \MonorepoBuilder20220527\Nette\Utils\Strings::match($fileContents, \MonorepoBuilder20220527\Symplify\EasyTesting\ValueObject\SplitLine::SPLIT_LINE_REGEX);
            if ($hasNameSkipStart && $hasSplitLine) {
                $incorrectSkips[] = $fixtureFileInfo;
                continue;
            }
            if (!$hasNameSkipStart && !$hasSplitLine) {
                $missingSkips[] = $fixtureFileInfo;
            }
        }
        return new \MonorepoBuilder20220527\Symplify\EasyTesting\ValueObject\IncorrectAndMissingSkips($incorrectSkips, $missingSkips);
    }
    private function hasNameSkipStart(\MonorepoBuilder20220527\Symplify\SmartFileSystem\SmartFileInfo $fixtureFileInfo) : bool
    {
        return (bool) \MonorepoBuilder20220527\Nette\Utils\Strings::match($fixtureFileInfo->getBasenameWithoutSuffix(), \MonorepoBuilder20220527\Symplify\EasyTesting\ValueObject\Prefix::SKIP_PREFIX_REGEX);
    }
}
