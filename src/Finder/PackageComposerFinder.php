<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Finder;

use MonorepoBuilderPrefix202311\Symfony\Component\Finder\Finder;
use Symplify\MonorepoBuilder\Exception\ConfigurationException;
use Symplify\MonorepoBuilder\ValueObject\Option;
use MonorepoBuilderPrefix202311\Symplify\PackageBuilder\Parameter\ParameterProvider;
use MonorepoBuilderPrefix202311\Symplify\SmartFileSystem\Finder\FinderSanitizer;
use MonorepoBuilderPrefix202311\Symplify\SmartFileSystem\SmartFileInfo;
/**
 * @see \Symplify\MonorepoBuilder\Tests\Finder\PackageComposerFinder\PackageComposerFinderTest
 */
final class PackageComposerFinder
{
    /**
     * @var \Symplify\SmartFileSystem\Finder\FinderSanitizer
     */
    private $finderSanitizer;
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
        return \defined('MonorepoBuilderPrefix202311\\PHPUNIT_COMPOSER_INSTALL') || \defined('MonorepoBuilderPrefix202311\\__PHPUNIT_PHAR__');
    }
}
