<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Finder;

use MonorepoBuilder20211223\Symfony\Component\Finder\Finder;
use Symplify\MonorepoBuilder\Exception\ConfigurationException;
use Symplify\MonorepoBuilder\ValueObject\Option;
use MonorepoBuilder20211223\Symplify\PackageBuilder\Parameter\ParameterProvider;
use MonorepoBuilder20211223\Symplify\SmartFileSystem\Finder\FinderSanitizer;
use MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo;
/**
 * @see \Symplify\MonorepoBuilder\Tests\Finder\PackageComposerFinder\PackageComposerFinderTest
 */
final class PackageComposerFinder
{
    /**
     * @var string[]
     */
    private $packageDirectories = [];
    /**
     * @var string[]
     */
    private $packageDirectoriesExcludes = [];
    /**
     * @var SmartFileInfo[]
     */
    private $cachedPackageComposerFiles = [];
    /**
     * @var \Symplify\SmartFileSystem\Finder\FinderSanitizer
     */
    private $finderSanitizer;
    public function __construct(\MonorepoBuilder20211223\Symplify\PackageBuilder\Parameter\ParameterProvider $parameterProvider, \MonorepoBuilder20211223\Symplify\SmartFileSystem\Finder\FinderSanitizer $finderSanitizer)
    {
        $this->finderSanitizer = $finderSanitizer;
        $this->packageDirectories = $parameterProvider->provideArrayParameter(\Symplify\MonorepoBuilder\ValueObject\Option::PACKAGE_DIRECTORIES);
        $this->packageDirectoriesExcludes = $parameterProvider->provideArrayParameter(\Symplify\MonorepoBuilder\ValueObject\Option::PACKAGE_DIRECTORIES_EXCLUDES);
    }
    public function getRootPackageComposerFile() : \MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo
    {
        return new \MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo(\getcwd() . \DIRECTORY_SEPARATOR . 'composer.json');
    }
    /**
     * @return SmartFileInfo[]
     */
    public function getPackageComposerFiles() : array
    {
        if ($this->packageDirectories === []) {
            $errorMessage = \sprintf('First define package directories in "monorepo-builder.php" config.%sUse $parameters->set(Option::%s, "...");', \PHP_EOL, \Symplify\MonorepoBuilder\ValueObject\Option::PACKAGE_DIRECTORIES);
            throw new \Symplify\MonorepoBuilder\Exception\ConfigurationException($errorMessage);
        }
        if ($this->cachedPackageComposerFiles === []) {
            $finder = \MonorepoBuilder20211223\Symfony\Component\Finder\Finder::create()->files()->in($this->packageDirectories)->exclude('compiler')->exclude('templates')->exclude('vendor')->exclude('build')->exclude('node_modules')->name('composer.json');
            if ($this->packageDirectoriesExcludes !== []) {
                $finder->exclude($this->packageDirectoriesExcludes);
            }
            if (!$this->isPHPUnit()) {
                $finder->notPath('#tests#');
            }
            $this->cachedPackageComposerFiles = $this->finderSanitizer->sanitize($finder);
        }
        return $this->cachedPackageComposerFiles;
    }
    private function isPHPUnit() : bool
    {
        // defined by PHPUnit
        return \defined('PHPUNIT_COMPOSER_INSTALL') || \defined('__PHPUNIT_PHAR__');
    }
}
