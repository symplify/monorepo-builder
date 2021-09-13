<?php

declare (strict_types=1);
namespace MonorepoBuilder20210913\Symplify\EasyTesting\FixtureSplitter;

use MonorepoBuilder20210913\Nette\Utils\Strings;
use MonorepoBuilder20210913\Symplify\EasyTesting\ValueObject\FixtureSplit\TrioContent;
use MonorepoBuilder20210913\Symplify\EasyTesting\ValueObject\SplitLine;
use MonorepoBuilder20210913\Symplify\SmartFileSystem\SmartFileInfo;
use MonorepoBuilder20210913\Symplify\SymplifyKernel\Exception\ShouldNotHappenException;
final class TrioFixtureSplitter
{
    public function splitFileInfo(\MonorepoBuilder20210913\Symplify\SmartFileSystem\SmartFileInfo $smartFileInfo) : \MonorepoBuilder20210913\Symplify\EasyTesting\ValueObject\FixtureSplit\TrioContent
    {
        $parts = \MonorepoBuilder20210913\Nette\Utils\Strings::split($smartFileInfo->getContents(), \MonorepoBuilder20210913\Symplify\EasyTesting\ValueObject\SplitLine::SPLIT_LINE_REGEX);
        $this->ensureHasThreeParts($parts, $smartFileInfo);
        return new \MonorepoBuilder20210913\Symplify\EasyTesting\ValueObject\FixtureSplit\TrioContent($parts[0], $parts[1], $parts[2]);
    }
    /**
     * @param mixed[] $parts
     */
    private function ensureHasThreeParts(array $parts, \MonorepoBuilder20210913\Symplify\SmartFileSystem\SmartFileInfo $smartFileInfo) : void
    {
        if (\count($parts) === 3) {
            return;
        }
        $message = \sprintf('The fixture "%s" should have 3 parts. %d found', $smartFileInfo->getRelativeFilePathFromCwd(), \count($parts));
        throw new \MonorepoBuilder20210913\Symplify\SymplifyKernel\Exception\ShouldNotHappenException($message);
    }
}
