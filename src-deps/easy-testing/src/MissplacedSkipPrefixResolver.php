<?php

declare(strict_types=1);

namespace Symplify\EasyTesting;

use Nette\Utils\Strings;
use Symplify\EasyTesting\ValueObject\IncorrectAndMissingSkips;
use Symplify\EasyTesting\ValueObject\Prefix;
use Symplify\EasyTesting\ValueObject\SplitLine;
use Symplify\SmartFileSystem\SmartFileInfo;

/**
 * @see \Symplify\EasyTesting\Tests\MissingSkipPrefixResolver\MissingSkipPrefixResolverTest
 */
final class MissplacedSkipPrefixResolver
{
    /**
     * @param SmartFileInfo[] $fixtureFileInfos
     */
    public function resolve(array $fixtureFileInfos): IncorrectAndMissingSkips
    {
        $incorrectSkips = [];
        $missingSkips = [];

        foreach ($fixtureFileInfos as $fixtureFileInfo) {
            $hasNameSkipStart = $this->hasNameSkipStart($fixtureFileInfo);
            $fileContents = $fixtureFileInfo->getContents();
            $hasSplitLine = (bool) Strings::match($fileContents, SplitLine::SPLIT_LINE_REGEX);

            if ($hasNameSkipStart && $hasSplitLine) {
                $incorrectSkips[] = $fixtureFileInfo;
                continue;
            }

            if (! $hasNameSkipStart && ! $hasSplitLine) {
                $missingSkips[] = $fixtureFileInfo;
            }
        }

        return new IncorrectAndMissingSkips($incorrectSkips, $missingSkips);
    }

    private function hasNameSkipStart(SmartFileInfo $fixtureFileInfo): bool
    {
        return (bool) Strings::match($fixtureFileInfo->getBasenameWithoutSuffix(), Prefix::SKIP_PREFIX_REGEX);
    }
}
