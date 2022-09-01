<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Finder;

use MonorepoBuilder202209\Symfony\Component\Finder\Finder;
use Symplify\MonorepoBuilder\Exception\ConfigurationException;
use Symplify\MonorepoBuilder\ValueObject\Option;
use MonorepoBuilder202209\Symplify\PackageBuilder\Parameter\ParameterProvider;
use MonorepoBuilder202209\Symplify\SmartFileSystem\Finder\FinderSanitizer;
use MonorepoBuilder202209\Symplify\SmartFileSystem\SmartFileInfo;
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
    public function __construct(ParameterProvider $parameterProvider, FinderSanitizer $finderSanitizer)
    {
        $this->finderSanitizer = $finderSanitizer;
        $this->packageDirectories = $parameterProvider->provideArrayParameter(Option::PACKAGE_DIRECTORIES);
        $this->packageDirectoriesExcludes = $parameterProvider->provideArrayParameter(Option::PACKAGE_DIRECTORIES_EXCLUDES);
    }
    public function getRootPackageComposerFile() : SmartFileInfo
    {
        return new SmartFileInfo(\getcwd() . \DIRECTORY_SEPARATOR . 'composer.json');
    }
    /**
     * @return SmartFileInfo[]
     */
    public function getPackageComposerFiles() : array
    {
        if ($this->packageDirectories === []) {
            $errorMessage = \sprintf('First define package directories in "monorepo-builder.php" config.%sUse $parameters->set(Option::%s, "...");', \PHP_EOL, Option::PACKAGE_DIRECTORIES);
            throw new ConfigurationException($errorMessage);
        }
        if ($this->cachedPackageComposerFiles === []) {
            $finder = Finder::create()->files()->in($this->packageDirectories)->exclude('compiler')->exclude('templates')->exclude('vendor')->exclude('build')->exclude('node_modules')->name('composer.json');
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
        return \defined('MonorepoBuilder202209\\PHPUNIT_COMPOSER_INSTALL') || \defined('MonorepoBuilder202209\\__PHPUNIT_PHAR__');
    }
}
