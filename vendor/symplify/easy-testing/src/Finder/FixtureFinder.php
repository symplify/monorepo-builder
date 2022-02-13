<?php

declare (strict_types=1);
namespace MonorepoBuilder20220213\Symplify\EasyTesting\Finder;

use MonorepoBuilder20220213\Symfony\Component\Finder\Finder;
use MonorepoBuilder20220213\Symplify\SmartFileSystem\Finder\FinderSanitizer;
use MonorepoBuilder20220213\Symplify\SmartFileSystem\SmartFileInfo;
final class FixtureFinder
{
    /**
     * @var \Symplify\SmartFileSystem\Finder\FinderSanitizer
     */
    private $finderSanitizer;
    public function __construct(\MonorepoBuilder20220213\Symplify\SmartFileSystem\Finder\FinderSanitizer $finderSanitizer)
    {
        $this->finderSanitizer = $finderSanitizer;
    }
    /**
     * @return SmartFileInfo[]
     */
    public function find(array $sources) : array
    {
        $finder = new \MonorepoBuilder20220213\Symfony\Component\Finder\Finder();
        $finder->files()->in($sources)->name('*.php.inc')->path('Fixture')->sortByName();
        return $this->finderSanitizer->sanitize($finder);
    }
}
