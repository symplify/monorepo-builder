<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\ComposerJsonManipulator\Sorter;

use MonorepoBuilderPrefix202311\Nette\Utils\Strings;
/**
 * Mostly inspired by https://github.com/composer/composer/blob/master/src/Composer/Json/JsonManipulator.php
 *
 * @see \Symplify\MonorepoBuilder\Tests\ComposerJsonManipulator\Sorter\ComposerPackageSorterTest
 */
final class ComposerPackageSorter
{
    /**
     * @see https://regex101.com/r/tMrjMY/1
     * @var string
     */
    private const PLATFORM_PACKAGE_REGEX = '#^(?:php(?:-64bit|-ipv6|-zts|-debug)?|hhvm|(?:ext|lib)-[a-z0-9](?:[_.-]?[a-z0-9]+)*|composer-(?:plugin|runtime)-api)$#iD';
    /**
     * @see https://regex101.com/r/SXZcfb/1
     * @var string
     */
    private const REQUIREMENT_TYPE_REGEX = '#^(?<name>php|hhvm|ext|lib|\\D)#';
    /**
     * Sorts packages by importance (platform packages first, then PHP dependencies) and alphabetically.
     *
     * @link https://getcomposer.org/doc/02-libraries.md#platform-packages
     *
     * @param array<string, string> $packages
     * @return array<string, string>
     */
    public function sortPackages(array $packages) : array
    {
        \uksort($packages, function (string $firstPackageName, string $secondPackageName) : int {
            return $this->createNameWithPriority($firstPackageName) <=> $this->createNameWithPriority($secondPackageName);
        });
        return $packages;
    }
    private function createNameWithPriority(string $requirementName) : string
    {
        if ($this->isPlatformPackage($requirementName)) {
            return Strings::replace($requirementName, self::REQUIREMENT_TYPE_REGEX, static function (array $match) : string {
                $name = $match['name'];
                if ($name === 'php') {
                    return '0-' . $name;
                }
                if ($name === 'hhvm') {
                    return '0-' . $name;
                }
                if ($name === 'ext') {
                    return '1-' . $name;
                }
                if ($name === 'lib') {
                    return '2-' . $name;
                }
                return '3-' . $name;
            });
        }
        return '4-' . $requirementName;
    }
    private function isPlatformPackage(string $name) : bool
    {
        return (bool) Strings::match($name, self::PLATFORM_PACKAGE_REGEX);
    }
}
