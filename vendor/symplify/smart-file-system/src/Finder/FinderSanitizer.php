<?php

declare (strict_types=1);
namespace MonorepoBuilderPrefix202308\Symplify\SmartFileSystem\Finder;

use MonorepoBuilderPrefix202308\Symfony\Component\Finder\Finder;
use MonorepoBuilderPrefix202308\Symfony\Component\Finder\SplFileInfo;
use MonorepoBuilderPrefix202308\Symplify\SmartFileSystem\SmartFileInfo;
final class FinderSanitizer
{
    /**
     * @return SmartFileInfo[]
     */
    public function sanitize(Finder $finder) : array
    {
        $smartFileInfos = [];
        foreach ($finder as $fileInfo) {
            if (!$this->isFileInfoValid($fileInfo)) {
                continue;
            }
            /** @var string $realPath */
            $realPath = $fileInfo->getRealPath();
            $smartFileInfos[] = new SmartFileInfo($realPath);
        }
        return $smartFileInfos;
    }
    private function isFileInfoValid(SplFileInfo $fileInfo) : bool
    {
        return (bool) $fileInfo->getRealPath();
    }
}
