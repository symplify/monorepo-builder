<?php

declare (strict_types=1);
namespace MonorepoBuilder20220414\Symplify\EasyTesting\FixtureSplitter;

use MonorepoBuilder20220414\Nette\Utils\Strings;
use MonorepoBuilder20220414\Symplify\EasyTesting\ValueObject\FixtureSplit\TrioContent;
use MonorepoBuilder20220414\Symplify\EasyTesting\ValueObject\SplitLine;
use MonorepoBuilder20220414\Symplify\SmartFileSystem\SmartFileInfo;
use MonorepoBuilder20220414\Symplify\SymplifyKernel\Exception\ShouldNotHappenException;
/**
 * @api
 */
final class TrioFixtureSplitter
{
    public function splitFileInfo(\MonorepoBuilder20220414\Symplify\SmartFileSystem\SmartFileInfo $smartFileInfo) : \MonorepoBuilder20220414\Symplify\EasyTesting\ValueObject\FixtureSplit\TrioContent
    {
        $parts = \MonorepoBuilder20220414\Nette\Utils\Strings::split($smartFileInfo->getContents(), \MonorepoBuilder20220414\Symplify\EasyTesting\ValueObject\SplitLine::SPLIT_LINE_REGEX);
        $this->ensureHasThreeParts($parts, $smartFileInfo);
        return new \MonorepoBuilder20220414\Symplify\EasyTesting\ValueObject\FixtureSplit\TrioContent($parts[0], $parts[1], $parts[2]);
    }
    /**
     * @param mixed[] $parts
     */
    private function ensureHasThreeParts(array $parts, \MonorepoBuilder20220414\Symplify\SmartFileSystem\SmartFileInfo $smartFileInfo) : void
    {
        if (\count($parts) === 3) {
            return;
        }
        $message = \sprintf('The fixture "%s" should have 3 parts. %d found', $smartFileInfo->getRelativeFilePathFromCwd(), \count($parts));
        throw new \MonorepoBuilder20220414\Symplify\SymplifyKernel\Exception\ShouldNotHappenException($message);
    }
}
