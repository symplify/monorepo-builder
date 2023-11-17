<?php

declare (strict_types=1);
namespace MonorepoBuilderPrefix202311\Symplify\EasyTesting\ValueObject;

use MonorepoBuilderPrefix202311\Symplify\SmartFileSystem\SmartFileInfo;
final class IncorrectAndMissingSkips
{
    /**
     * @var SmartFileInfo[]
     */
    private $incorrectSkipFileInfos;
    /**
     * @var SmartFileInfo[]
     */
    private $missingSkipFileInfos;
    /**
     * @param SmartFileInfo[] $incorrectSkipFileInfos
     * @param SmartFileInfo[] $missingSkipFileInfos
     */
    public function __construct(array $incorrectSkipFileInfos, array $missingSkipFileInfos)
    {
        $this->incorrectSkipFileInfos = $incorrectSkipFileInfos;
        $this->missingSkipFileInfos = $missingSkipFileInfos;
    }
    /**
     * @return SmartFileInfo[]
     */
    public function getIncorrectSkipFileInfos() : array
    {
        return $this->incorrectSkipFileInfos;
    }
    /**
     * @return SmartFileInfo[]
     */
    public function getMissingSkipFileInfos() : array
    {
        return $this->missingSkipFileInfos;
    }
    public function getFileCount() : int
    {
        return \count($this->missingSkipFileInfos) + \count($this->incorrectSkipFileInfos);
    }
}
