<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder;

use Symplify\MonorepoBuilder\ComposerJsonManipulator\FileSystem\JsonFileManager;
use Symplify\MonorepoBuilder\ValueObject\Option;
use MonorepoBuilderPrefix202311\Symplify\PackageBuilder\Parameter\ParameterProvider;
use MonorepoBuilderPrefix202311\Symplify\SmartFileSystem\SmartFileInfo;
/**
 * @see \Symplify\MonorepoBuilder\Tests\DevMasterAliasUpdater\DevMasterAliasUpdaterTest
 */
final class DevMasterAliasUpdater
{
    /**
     * @var \Symplify\MonorepoBuilder\ComposerJsonManipulator\FileSystem\JsonFileManager
     */
    private $jsonFileManager;
    /**
     * @var string
     */
    private const EXTRA = 'extra';
    /**
     * @var string
     */
    private const BRANCH_ALIAS = 'branch-alias';
    /**
     * @var string
     */
    private const COMPOSER_BRANCH_PREFIX = 'dev-';
    /**
     * @var string
     */
    private $branchAliasTarget;
    public function __construct(JsonFileManager $jsonFileManager, ParameterProvider $parameterProvider)
    {
        $this->jsonFileManager = $jsonFileManager;
        $this->branchAliasTarget = self::COMPOSER_BRANCH_PREFIX . $parameterProvider->provideStringParameter(Option::DEFAULT_BRANCH_NAME);
    }
    /**
     * @param SmartFileInfo[] $fileInfos
     */
    public function updateFileInfosWithAlias(array $fileInfos, string $alias) : void
    {
        foreach ($fileInfos as $fileInfo) {
            $json = $this->jsonFileManager->loadFromFileInfo($fileInfo);
            if ($this->shouldSkip($json, $alias)) {
                continue;
            }
            $json[self::EXTRA][self::BRANCH_ALIAS][$this->branchAliasTarget] = $alias;
            $this->jsonFileManager->printJsonToFileInfo($json, $fileInfo);
        }
    }
    /**
     * @param mixed[] $json
     */
    private function shouldSkip(array $json, string $alias) : bool
    {
        // update only when already present
        if (!isset($json[self::EXTRA][self::BRANCH_ALIAS][$this->branchAliasTarget])) {
            return \true;
        }
        $currentAlias = $json[self::EXTRA][self::BRANCH_ALIAS][$this->branchAliasTarget];
        return $currentAlias === $alias;
    }
}
