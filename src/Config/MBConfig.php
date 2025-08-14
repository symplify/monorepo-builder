<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Config;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use Symplify\MonorepoBuilder\ValueObject\Option;
use Webmozart\Assert\Assert;

final class MBConfig extends ContainerConfigurator
{
    private static bool $disableDefaultWorkers = false;

    /**
     * @param string[] $packageDirectories
     */
    public function packageDirectories(array $packageDirectories): void
    {
        Assert::allFileExists($packageDirectories);

        $parameters = $this->parameters();
        $parameters->set(Option::PACKAGE_DIRECTORIES, $packageDirectories);
    }

    public static function isDisableDefaultWorkers(): bool
    {
        return self::$disableDefaultWorkers;
    }

    public static function disableDefaultWorkers(): void
    {
        self::$disableDefaultWorkers = true;
    }

    /**
     * @param string[] $packageDirectories
     */
    public function packageDirectoriesExcludes(array $packageDirectories): void
    {
        $parameters = $this->parameters();
        $parameters->set(Option::PACKAGE_DIRECTORIES_EXCLUDES, $packageDirectories);
    }

    public function defaultBranch(string $defaultBranch): void
    {
        $parameters = $this->parameters();
        $parameters->set(Option::DEFAULT_BRANCH_NAME, $defaultBranch);
    }

    /**
     * @param array<string, mixed> $dataToRemove
     */
    public function dataToRemove(array $dataToRemove): void
    {
        $parameters = $this->parameters();
        $parameters->set(Option::DATA_TO_REMOVE, $dataToRemove);
    }

    /**
     * @param array<string, mixed> $dataToAppend
     */
    public function dataToAppend(array $dataToAppend): void
    {
        $parameters = $this->parameters();
        $parameters->set(Option::DATA_TO_APPEND, $dataToAppend);
    }

    /**
     * @param array<class-string<ReleaseWorkerInterface>> $workerClasses
     */
    public function workers(array $workerClasses): void
    {
        $services = $this->services();

        foreach ($workerClasses as $workerClass) {
            $services->set($workerClass);
        }
    }

    public function packageAliasFormat(string $packageAliasFormat): void
    {
        $parameters = $this->parameters();
        $parameters->set(Option::PACKAGE_ALIAS_FORMAT, $packageAliasFormat);
    }

    /**
     * @param string[] $sectionOrder
     */
    public function composerSectionOrder(array $sectionOrder): void
    {
        $parameters = $this->parameters();
        $parameters->set(Option::SECTION_ORDER, $sectionOrder);
    }
}
