<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Testing\ComposerJson;

use MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use Symplify\MonorepoBuilder\ValueObject\Option;
use MonorepoBuilder20211223\Symplify\PackageBuilder\Parameter\ParameterProvider;
final class ComposerVersionManipulator
{
    /**
     * @var string
     */
    private const COMPOSER_BRANCH_PREFIX = 'dev-';
    /**
     * @var string
     */
    private $branchAliasTarget;
    public function __construct(\MonorepoBuilder20211223\Symplify\PackageBuilder\Parameter\ParameterProvider $parameterProvider)
    {
        $this->branchAliasTarget = self::COMPOSER_BRANCH_PREFIX . $parameterProvider->provideStringParameter(\Symplify\MonorepoBuilder\ValueObject\Option::DEFAULT_BRANCH_NAME);
    }
    /**
     * @param mixed[] $packageComposerJson
     * @param string[] $usedPackageNames
     * @return mixed[]
     */
    public function decorateAsteriskVersionForUsedPackages(array $packageComposerJson, array $usedPackageNames) : array
    {
        foreach ([\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::REQUIRE, \MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::REQUIRE_DEV] as $section) {
            foreach ($usedPackageNames as $usedPackageName) {
                if (!isset($packageComposerJson[$section][$usedPackageName])) {
                    continue;
                }
                $packageComposerJson[$section][$usedPackageName] = $this->branchAliasTarget;
            }
        }
        return $packageComposerJson;
    }
}
