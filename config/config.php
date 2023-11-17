<?php

declare (strict_types=1);
namespace MonorepoBuilderPrefix202311;

use MonorepoBuilderPrefix202311\Symfony\Component\Console\Application;
use MonorepoBuilderPrefix202311\Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use Symplify\MonorepoBuilder\Config\MBConfig;
use Symplify\MonorepoBuilder\Console\MonorepoBuilderApplication;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\PushTagReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\TagVersionReleaseWorker;
use Symplify\MonorepoBuilder\ValueObject\Option;
use MonorepoBuilderPrefix202311\Symplify\PackageBuilder\Console\Style\SymfonyStyleFactory;
use MonorepoBuilderPrefix202311\Symplify\PackageBuilder\Parameter\ParameterProvider;
use MonorepoBuilderPrefix202311\Symplify\PackageBuilder\Reflection\PrivatesCaller;
use MonorepoBuilderPrefix202311\Symplify\PackageBuilder\Yaml\ParametersMerger;
use function MonorepoBuilderPrefix202311\Symfony\Component\DependencyInjection\Loader\Configurator\service;
return static function (MBConfig $mbConfig) : void {
    $parameters = $mbConfig->parameters();
    $parameters->set('env(GITHUB_TOKEN)', null);
    $parameters->set(Option::GITHUB_TOKEN, '%env(GITHUB_TOKEN)%');
    $mbConfig->packageDirectories([]);
    $mbConfig->packageDirectoriesExcludes([]);
    $mbConfig->dataToAppend([]);
    $mbConfig->dataToRemove([]);
    $parameters->set(Option::EXCLUDE_PACKAGE_VERSION_CONFLICTS, []);
    $parameters->set(Option::IS_STAGE_REQUIRED, \false);
    $parameters->set(Option::STAGES_TO_ALLOW_EXISTING_TAG, []);
    // for back compatibility, better switch to "main"
    $mbConfig->defaultBranch('master');
    $mbConfig->packageAliasFormat('<major>.<minor>-dev');
    $mbConfig->composerSectionOrder([ComposerJsonSection::NAME, ComposerJsonSection::TYPE, ComposerJsonSection::DESCRIPTION, ComposerJsonSection::KEYWORDS, ComposerJsonSection::HOMEPAGE, ComposerJsonSection::LICENSE, ComposerJsonSection::AUTHORS, ComposerJsonSection::SUPPORT, ComposerJsonSection::FUNDING, ComposerJsonSection::BIN, ComposerJsonSection::REQUIRE, ComposerJsonSection::REQUIRE_DEV, ComposerJsonSection::AUTOLOAD, ComposerJsonSection::AUTOLOAD_DEV, ComposerJsonSection::REPOSITORIES, ComposerJsonSection::PROVIDE, ComposerJsonSection::CONFLICT, ComposerJsonSection::REPLACE, ComposerJsonSection::SCRIPTS, ComposerJsonSection::SCRIPTS_DESCRIPTIONS, ComposerJsonSection::SUGGEST, ComposerJsonSection::CONFIG, ComposerJsonSection::MINIMUM_STABILITY, ComposerJsonSection::PREFER_STABLE, ComposerJsonSection::EXTRA]);
    $services = $mbConfig->services();
    $services->defaults()->public()->autowire();
    $services->load('Symplify\MonorepoBuilder\\', __DIR__ . '/../packages')->exclude([
        // register manually
        __DIR__ . '/../packages/Release/ReleaseWorker',
    ]);
    if (!$mbConfig->isDisableDefaultWorkers()) {
        // add default for easy tag and push without need to make configs
        $services->set(TagVersionReleaseWorker::class);
        $services->set(PushTagReleaseWorker::class);
    }
    $services->load('Symplify\MonorepoBuilder\\', __DIR__ . '/../src')->exclude([__DIR__ . '/../src/Config/MBConfig.php', __DIR__ . '/../src/Exception', __DIR__ . '/../src/Kernel', __DIR__ . '/../src/ValueObject']);
    // for autowired commands
    $services->alias(Application::class, MonorepoBuilderApplication::class);
    $services->set(PrivatesCaller::class);
    $services->set(ParametersMerger::class);
    $services->set(PrivatesCaller::class);
    $services->set(ParameterProvider::class)->args([service('service_container')]);
    $services->set(SymfonyStyleFactory::class);
    $services->set(SymfonyStyle::class)->factory([service(SymfonyStyleFactory::class), 'create']);
};
