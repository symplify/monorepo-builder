<?php

declare (strict_types=1);
namespace MonorepoBuilder20211223\Symplify\PackageBuilder\Console\Command;

use MonorepoBuilder20211223\Symfony\Component\Console\Command\Command;
use MonorepoBuilder20211223\Symfony\Component\Console\Input\InputOption;
use MonorepoBuilder20211223\Symfony\Component\Console\Style\SymfonyStyle;
use MonorepoBuilder20211223\Symfony\Contracts\Service\Attribute\Required;
use MonorepoBuilder20211223\Symplify\PackageBuilder\ValueObject\Option;
use MonorepoBuilder20211223\Symplify\SmartFileSystem\FileSystemGuard;
use MonorepoBuilder20211223\Symplify\SmartFileSystem\Finder\SmartFinder;
use MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileSystem;
abstract class AbstractSymplifyCommand extends \MonorepoBuilder20211223\Symfony\Component\Console\Command\Command
{
    /**
     * @var \Symfony\Component\Console\Style\SymfonyStyle
     */
    protected $symfonyStyle;
    /**
     * @var \Symplify\SmartFileSystem\SmartFileSystem
     */
    protected $smartFileSystem;
    /**
     * @var \Symplify\SmartFileSystem\Finder\SmartFinder
     */
    protected $smartFinder;
    /**
     * @var \Symplify\SmartFileSystem\FileSystemGuard
     */
    protected $fileSystemGuard;
    public function __construct()
    {
        parent::__construct();
        $this->addOption(\MonorepoBuilder20211223\Symplify\PackageBuilder\ValueObject\Option::CONFIG, 'c', \MonorepoBuilder20211223\Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED, 'Path to config file');
    }
    /**
     * @required
     */
    public function autowire(\MonorepoBuilder20211223\Symfony\Component\Console\Style\SymfonyStyle $symfonyStyle, \MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileSystem $smartFileSystem, \MonorepoBuilder20211223\Symplify\SmartFileSystem\Finder\SmartFinder $smartFinder, \MonorepoBuilder20211223\Symplify\SmartFileSystem\FileSystemGuard $fileSystemGuard) : void
    {
        $this->symfonyStyle = $symfonyStyle;
        $this->smartFileSystem = $smartFileSystem;
        $this->smartFinder = $smartFinder;
        $this->fileSystemGuard = $fileSystemGuard;
    }
}
