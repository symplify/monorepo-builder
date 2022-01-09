<?php

declare (strict_types=1);
namespace MonorepoBuilder20220109;

use MonorepoBuilder20220109\Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\MonorepoBuilder\Command\BumpInterdependencyCommand;
use Symplify\MonorepoBuilder\Command\PackageAliasCommand;
use Symplify\MonorepoBuilder\Command\PackagesJsonCommand;
use Symplify\MonorepoBuilder\Command\ValidateCommand;
use Symplify\MonorepoBuilder\Init\Command\InitCommand;
use Symplify\MonorepoBuilder\Merge\Command\MergeCommand;
use Symplify\MonorepoBuilder\Propagate\Command\PropagateCommand;
use Symplify\MonorepoBuilder\Release\Command\ReleaseCommand;
use Symplify\MonorepoBuilder\Testing\Command\LocalizeComposerPathsCommand;
use MonorepoBuilder20220109\Symplify\PackageBuilder\Reflection\PrivatesCaller;
use MonorepoBuilder20220109\Symplify\PackageBuilder\Yaml\ParametersMerger;
use function MonorepoBuilder20220109\Symfony\Component\DependencyInjection\Loader\Configurator\service;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire()->autoconfigure();
    $services->load('Symplify\MonorepoBuilder\\', __DIR__ . '/../src')->exclude([__DIR__ . '/../src/Exception', __DIR__ . '/../src/Kernel', __DIR__ . '/../src/ValueObject']);
    // console
    $services->set(\MonorepoBuilder20220109\Symfony\Component\Console\Application::class)->call('addCommands', [[\MonorepoBuilder20220109\Symfony\Component\DependencyInjection\Loader\Configurator\service(\Symplify\MonorepoBuilder\Command\BumpInterdependencyCommand::class), \MonorepoBuilder20220109\Symfony\Component\DependencyInjection\Loader\Configurator\service(\Symplify\MonorepoBuilder\Init\Command\InitCommand::class), \MonorepoBuilder20220109\Symfony\Component\DependencyInjection\Loader\Configurator\service(\Symplify\MonorepoBuilder\Testing\Command\LocalizeComposerPathsCommand::class), \MonorepoBuilder20220109\Symfony\Component\DependencyInjection\Loader\Configurator\service(\Symplify\MonorepoBuilder\Merge\Command\MergeCommand::class), \MonorepoBuilder20220109\Symfony\Component\DependencyInjection\Loader\Configurator\service(\Symplify\MonorepoBuilder\Command\PackageAliasCommand::class), \MonorepoBuilder20220109\Symfony\Component\DependencyInjection\Loader\Configurator\service(\Symplify\MonorepoBuilder\Command\PackagesJsonCommand::class), \MonorepoBuilder20220109\Symfony\Component\DependencyInjection\Loader\Configurator\service(\Symplify\MonorepoBuilder\Propagate\Command\PropagateCommand::class), \MonorepoBuilder20220109\Symfony\Component\DependencyInjection\Loader\Configurator\service(\Symplify\MonorepoBuilder\Release\Command\ReleaseCommand::class), \MonorepoBuilder20220109\Symfony\Component\DependencyInjection\Loader\Configurator\service(\Symplify\MonorepoBuilder\Command\ValidateCommand::class)]]);
    $services->set(\MonorepoBuilder20220109\Symplify\PackageBuilder\Reflection\PrivatesCaller::class);
    $services->set(\MonorepoBuilder20220109\Symplify\PackageBuilder\Yaml\ParametersMerger::class);
};
