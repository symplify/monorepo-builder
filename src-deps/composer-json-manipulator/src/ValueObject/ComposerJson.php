<?php

declare(strict_types=1);

namespace Symplify\ComposerJsonManipulator\ValueObject;

use Nette\Utils\Arrays;
use Nette\Utils\Strings;
use Symplify\ComposerJsonManipulator\Sorter\ComposerPackageSorter;
use Symplify\SmartFileSystem\SmartFileInfo;
use Symplify\SymplifyKernel\Exception\ShouldNotHappenException;

/**
 * @api
 * @see \Symplify\ComposerJsonManipulator\Tests\ValueObject\ComposerJsonTest
 */
final class ComposerJson
{
    /**
     * @var string
     */
    private const CLASSMAP_KEY = 'classmap';

    /**
     * @var string
     */
    private const PHP = 'php';

    private ?string $name = null;

    private ?string $description = null;

    /**
     * @var string[]
     */
    private array $keywords = [];

    private ?string $homepage = null;

    /**
     * @var string|string[]|null
     */
    private array|string|null $license = null;

    private ?string $minimumStability = null;

    private ?bool $preferStable = null;

    /**
     * @var mixed[]
     */
    private array $repositories = [];

    /**
     * @var array<string, string>
     */
    private array $require = [];

    /**
     * @var mixed[]
     */
    private array $autoload = [];

    /**
     * @var mixed[]
     */
    private array $extra = [];

    /**
     * @var array<string, string>
     */
    private array $requireDev = [];

    /**
     * @var mixed[]
     */
    private array $autoloadDev = [];

    /**
     * @var string[]
     */
    private array $orderedKeys = [];

    /**
     * @var array<string, string>
     */
    private array $replace = [];

    /**
     * @var array<string, string|string[]>
     */
    private array $scripts = [];

    /**
     * @var mixed[]
     */
    private array $config = [];

    private ?SmartFileInfo $fileInfo = null;

    private ComposerPackageSorter $composerPackageSorter;

    /**
     * @var array<string, string>
     */
    private array $conflicts = [];

    /**
     * @var mixed[]
     */
    private array $bin = [];

    private ?string $type = null;

    /**
     * @var mixed[]
     */
    private array $authors = [];

    /**
     * @var array<string, string>
     */
    private array $scriptsDescriptions = [];

    /**
     * @var array<string, string>
     */
    private array $suggest = [];

    private ?string $version = null;

    /**
     * @var array<string, string>
     */
    private array $provide = [];

    public function __construct()
    {
        $this->composerPackageSorter = new ComposerPackageSorter();
    }

    public function setOriginalFileInfo(SmartFileInfo $fileInfo): void
    {
        $this->fileInfo = $fileInfo;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @param array<string, string> $require
     */
    public function setRequire(array $require): void
    {
        $this->require = $this->sortPackagesIfNeeded($require);
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    /**
     * @return mixed[]
     */
    public function getRequire(): array
    {
        return $this->require;
    }

    public function getRequirePhpVersion(): ?string
    {
        return $this->require[self::PHP] ?? null;
    }

    /**
     * @return array<string, string>
     */
    public function getRequireDev(): array
    {
        return $this->requireDev;
    }

    /**
     * @param array<string, string> $requireDev
     */
    public function setRequireDev(array $requireDev): void
    {
        $this->requireDev = $this->sortPackagesIfNeeded($requireDev);
    }

    /**
     * @param string[] $orderedKeys
     */
    public function setOrderedKeys(array $orderedKeys): void
    {
        $this->orderedKeys = $orderedKeys;
    }

    /**
     * @return string[]
     */
    public function getOrderedKeys(): array
    {
        return $this->orderedKeys;
    }

    /**
     * @return mixed[]
     */
    public function getAutoload(): array
    {
        return $this->autoload;
    }

    /**
     * @return string[]
     */
    public function getAbsoluteAutoloadDirectories(): array
    {
        if ($this->fileInfo === null) {
            throw new ShouldNotHappenException();
        }

        $autoloadDirectories = $this->getAutoloadDirectories();

        $absoluteAutoloadDirectories = [];

        foreach ($autoloadDirectories as $autoloadDirectory) {
            if (is_file($autoloadDirectory)) {
                // skip files
                continue;
            }

            $absoluteAutoloadDirectories[] = $this->resolveExistingAutoloadDirectory($autoloadDirectory);
        }

        return $absoluteAutoloadDirectories;
    }

    /**
     * @param mixed[] $autoload
     */
    public function setAutoload(array $autoload): void
    {
        $this->autoload = $autoload;
    }

    /**
     * @return mixed[]
     */
    public function getAutoloadDev(): array
    {
        return $this->autoloadDev;
    }

    /**
     * @param mixed[] $autoloadDev
     */
    public function setAutoloadDev(array $autoloadDev): void
    {
        $this->autoloadDev = $autoloadDev;
    }

    /**
     * @return mixed[]
     */
    public function getRepositories(): array
    {
        return $this->repositories;
    }

    /**
     * @param mixed[] $repositories
     */
    public function setRepositories(array $repositories): void
    {
        $this->repositories = $repositories;
    }

    public function setMinimumStability(string $minimumStability): void
    {
        $this->minimumStability = $minimumStability;
    }

    public function removeMinimumStability(): void
    {
        $this->minimumStability = null;
    }

    public function getMinimumStability(): ?string
    {
        return $this->minimumStability;
    }

    public function getPreferStable(): ?bool
    {
        return $this->preferStable;
    }

    public function setPreferStable(bool $preferStable): void
    {
        $this->preferStable = $preferStable;
    }

    public function removePreferStable(): void
    {
        $this->preferStable = null;
    }

    /**
     * @return mixed[]
     */
    public function getExtra(): array
    {
        return $this->extra;
    }

    /**
     * @param mixed[] $extra
     */
    public function setExtra(array $extra): void
    {
        $this->extra = $extra;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getVendorName(): ?string
    {
        if ($this->name === null) {
            return null;
        }

        [$vendor] = explode('/', $this->name);
        return $vendor;
    }

    public function getShortName(): ?string
    {
        if ($this->name === null) {
            return null;
        }

        return Strings::after($this->name, '/', -1);
    }

    /**
     * @return array<string, string>
     */
    public function getReplace(): array
    {
        return $this->replace;
    }

    public function isReplacePackageSet(string $packageName): bool
    {
        return isset($this->replace[$packageName]);
    }

    /**
     * @param array<string, string> $replace
     */
    public function setReplace(array $replace): void
    {
        ksort($replace);

        $this->replace = $replace;
    }

    public function setReplacePackage(string $packageName, string $version): void
    {
        $this->replace[$packageName] = $version;
    }

    /**
     * @return mixed[]
     */
    public function getJsonArray(): array
    {
        $array = array_filter([
            ComposerJsonSection::NAME => $this->name,
            ComposerJsonSection::DESCRIPTION => $this->description,
            ComposerJsonSection::KEYWORDS => $this->keywords,
            ComposerJsonSection::HOMEPAGE => $this->homepage,
            ComposerJsonSection::LICENSE => $this->license,
            ComposerJsonSection::AUTHORS => $this->authors,
            ComposerJsonSection::TYPE => $this->type,
            ComposerJsonSection::REQUIRE => $this->require,
            ComposerJsonSection::REQUIRE_DEV => $this->requireDev,
            ComposerJsonSection::AUTOLOAD => $this->autoload,
            ComposerJsonSection::AUTOLOAD_DEV => $this->autoloadDev,
            ComposerJsonSection::REPOSITORIES => $this->repositories,
            ComposerJsonSection::EXTRA => $this->extra,
            ComposerJsonSection::BIN => $this->bin,
            ComposerJsonSection::SCRIPTS => $this->scripts,
            ComposerJsonSection::SCRIPTS_DESCRIPTIONS => $this->scriptsDescriptions,
            ComposerJsonSection::SUGGEST => $this->suggest,
            ComposerJsonSection::CONFIG => $this->config,
            ComposerJsonSection::REPLACE => $this->replace,
            ComposerJsonSection::CONFLICT => $this->conflicts,
            ComposerJsonSection::PROVIDE => $this->provide,
            ComposerJsonSection::VERSION => $this->version,
        ]);

        if ($this->minimumStability !== null) {
            $array[ComposerJsonSection::MINIMUM_STABILITY] = $this->minimumStability;
            $this->moveValueToBack(ComposerJsonSection::MINIMUM_STABILITY);
        }

        if ($this->preferStable !== null) {
            $array[ComposerJsonSection::PREFER_STABLE] = $this->preferStable;
            $this->moveValueToBack(ComposerJsonSection::PREFER_STABLE);
        }

        return $this->sortItemsByOrderedListOfKeys($array, $this->orderedKeys);
    }

    /**
     * @param array<string, string|string[]> $scripts
     */
    public function setScripts(array $scripts): void
    {
        $this->scripts = $scripts;
    }

    /**
     * @param mixed[] $config
     */
    public function setConfig(array $config): void
    {
        $this->config = $config;
    }

    /**
     * @return mixed[]
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string[] $keywords
     */
    public function setKeywords(array $keywords): void
    {
        $this->keywords = $keywords;
    }

    /**
     * @return string[]
     */
    public function getKeywords(): array
    {
        return $this->keywords;
    }

    public function setHomepage(string $homepage): void
    {
        $this->homepage = $homepage;
    }

    public function getHomepage(): ?string
    {
        return $this->homepage;
    }

    /**
     * @param string|string[]|null $license
     */
    public function setLicense(string | array | null $license): void
    {
        $this->license = $license;
    }

    /**
     * @return string|string[]|null
     */
    public function getLicense(): string|array|null
    {
        return $this->license;
    }

    /**
     * @param mixed[] $authors
     */
    public function setAuthors(array $authors): void
    {
        $this->authors = $authors;
    }

    /**
     * @return mixed[]
     */
    public function getAuthors(): array
    {
        return $this->authors;
    }

    public function hasPackage(string $packageName): bool
    {
        if ($this->hasRequiredPackage($packageName)) {
            return true;
        }

        return $this->hasRequiredDevPackage($packageName);
    }

    public function hasRequiredPackage(string $packageName): bool
    {
        return isset($this->require[$packageName]);
    }

    public function hasRequiredDevPackage(string $packageName): bool
    {
        return isset($this->requireDev[$packageName]);
    }

    public function addRequiredPackage(string $packageName, string $version): void
    {
        if (! $this->hasPackage($packageName)) {
            $this->require[$packageName] = $version;
            $this->require = $this->sortPackagesIfNeeded($this->require);
        }
    }

    public function addRequiredDevPackage(string $packageName, string $version): void
    {
        if (! $this->hasPackage($packageName)) {
            $this->requireDev[$packageName] = $version;
            $this->requireDev = $this->sortPackagesIfNeeded($this->requireDev);
        }
    }

    public function changePackageVersion(string $packageName, string $version): void
    {
        if ($this->hasRequiredPackage($packageName)) {
            $this->require[$packageName] = $version;
        }

        if ($this->hasRequiredDevPackage($packageName)) {
            $this->requireDev[$packageName] = $version;
        }
    }

    public function movePackageToRequire(string $packageName): void
    {
        if (! $this->hasRequiredDevPackage($packageName)) {
            return;
        }

        $version = $this->requireDev[$packageName];
        $this->removePackage($packageName);
        $this->addRequiredPackage($packageName, $version);
    }

    public function movePackageToRequireDev(string $packageName): void
    {
        if (! $this->hasRequiredPackage($packageName)) {
            return;
        }

        $version = $this->require[$packageName];
        $this->removePackage($packageName);
        $this->addRequiredDevPackage($packageName, $version);
    }

    public function removePackage(string $packageName): void
    {
        unset($this->require[$packageName], $this->requireDev[$packageName]);
    }

    public function replacePackage(string $oldPackageName, string $newPackageName, string $targetVersion): void
    {
        if ($this->hasRequiredPackage($oldPackageName)) {
            unset($this->require[$oldPackageName]);
            $this->addRequiredPackage($newPackageName, $targetVersion);
        }

        if ($this->hasRequiredDevPackage($oldPackageName)) {
            unset($this->requireDev[$oldPackageName]);
            $this->addRequiredDevPackage($newPackageName, $targetVersion);
        }
    }

    public function getFileInfo(): ?SmartFileInfo
    {
        return $this->fileInfo;
    }

    /**
     * @param array<string, string> $conflicts
     */
    public function setConflicts(array $conflicts): void
    {
        $this->conflicts = $conflicts;
    }

    /**
     * @param mixed[] $bin
     */
    public function setBin(array $bin): void
    {
        $this->bin = $bin;
    }

    /**
     * @return mixed[]
     */
    public function getBin(): array
    {
        return $this->bin;
    }

    /**
     * @return string[]
     */
    public function getPsr4AndClassmapDirectories(): array
    {
        $psr4Directories = array_values($this->autoload['psr-4'] ?? []);
        $classmapDirectories = $this->autoload['classmap'] ?? [];

        return array_merge($psr4Directories, $classmapDirectories);
    }

    /**
     * @return array<string, string|string[]>
     */
    public function getScripts(): array
    {
        return $this->scripts;
    }

    /**
     * @return array<string, string>
     */
    public function getScriptsDescriptions(): array
    {
        return $this->scriptsDescriptions;
    }

    /**
     * @return array<string, string>
     */
    public function getSuggest(): array
    {
        return $this->suggest;
    }

    /**
     * @return string[]
     */
    public function getAllClassmaps(): array
    {
        $autoloadClassmaps = $this->autoload[self::CLASSMAP_KEY] ?? [];
        $autoloadDevClassmaps = $this->autoloadDev[self::CLASSMAP_KEY] ?? [];

        return array_merge($autoloadClassmaps, $autoloadDevClassmaps);
    }

    /**
     * @return array<string, string>
     */
    public function getConflicts(): array
    {
        return $this->conflicts;
    }

    /**
     * @api
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return string[]
     */
    public function getAutoloadDirectories(): array
    {
        $autoloadDirectories = array_merge(
            $this->getPsr4AndClassmapDirectories(),
            $this->getPsr4AndClassmapDevDirectories()
        );

        return Arrays::flatten($autoloadDirectories);
    }

    /**
     * @return string[]
     */
    public function getPsr4AndClassmapDevDirectories(): array
    {
        $psr4Directories = array_values($this->autoloadDev['psr-4'] ?? []);
        $classmapDirectories = $this->autoloadDev['classmap'] ?? [];

        return array_merge($psr4Directories, $classmapDirectories);
    }

    /**
     * @param array<string, string> $scriptsDescriptions
     */
    public function setScriptsDescriptions(array $scriptsDescriptions): void
    {
        $this->scriptsDescriptions = $scriptsDescriptions;
    }

    /**
     * @param array<string, string> $suggest
     */
    public function setSuggest(array $suggest): void
    {
        $this->suggest = $suggest;
    }

    /**
     * @return string[]
     */
    public function getDuplicatedRequirePackages(): array
    {
        $requiredPackageNames = $this->require;
        $requiredDevPackageNames = $this->requireDev;

        return array_intersect($requiredPackageNames, $requiredDevPackageNames);
    }

    /**
     * @return string[]
     */
    public function getRequirePackageNames(): array
    {
        return array_keys($this->require);
    }

    /**
     * @return array<string, string>
     */
    public function getProvide(): array
    {
        return $this->provide;
    }

    public function isProvidePackageSet(string $packageName): bool
    {
        return isset($this->provide[$packageName]);
    }

    /**
     * @param array<string, string> $provide
     */
    public function setProvide(array $provide): void
    {
        ksort($provide);

        $this->provide = $provide;
    }

    public function setProvidePackage(string $packageName, string $version): void
    {
        $this->provide[$packageName] = $version;
    }

    /**
     * @param ComposerJsonSection::* $valueName
     */
    private function moveValueToBack(string $valueName): void
    {
        $key = array_search($valueName, $this->orderedKeys, true);
        if ($key !== false) {
            unset($this->orderedKeys[$key]);
        }

        $this->orderedKeys[] = $valueName;
    }

    /**
     * 2. sort item by prescribed key order
     *
     * @see https://www.designcise.com/web/tutorial/how-to-sort-an-array-by-keys-based-on-order-in-a-secondary-array-in-php
     * @param array<string, mixed> $contentItems
     * @param string[] $orderedVisibleItems
     * @return mixed[]
     */
    private function sortItemsByOrderedListOfKeys(array $contentItems, array $orderedVisibleItems): array
    {
        uksort($contentItems, function ($firstContentItem, $secondContentItem) use ($orderedVisibleItems): int {
            $firstItemPosition = $this->findPosition($firstContentItem, $orderedVisibleItems);
            $secondItemPosition = $this->findPosition($secondContentItem, $orderedVisibleItems);

            if ($firstItemPosition === false) {
                // new item, put in the back
                return -1;
            }

            if ($secondItemPosition === false) {
                // new item, put in the back
                return -1;
            }

            return $firstItemPosition <=> $secondItemPosition;
        });

        return $contentItems;
    }

    private function resolveExistingAutoloadDirectory(string $autoloadDirectory): string
    {
        if ($this->fileInfo === null) {
            throw new ShouldNotHappenException();
        }

        $filePathCandidates = [
            $this->fileInfo->getPath() . DIRECTORY_SEPARATOR . $autoloadDirectory,
            // mostly tests
            getcwd() . DIRECTORY_SEPARATOR . $autoloadDirectory,
        ];

        foreach ($filePathCandidates as $filePathCandidate) {
            if (file_exists($filePathCandidate)) {
                return $filePathCandidate;
            }
        }

        return $autoloadDirectory;
    }

    /**
     * @param array<string, string> $packages
     * @return array<string, string>
     */
    private function sortPackagesIfNeeded(array $packages): array
    {
        $sortPackages = $this->config['sort-packages'] ?? false;
        if ($sortPackages) {
            return $this->composerPackageSorter->sortPackages($packages);
        }

        return $packages;
    }

    /**
     * @param string[] $items
     */
    private function findPosition(string $key, array $items): int | string | bool
    {
        return array_search($key, $items, true);
    }
}
