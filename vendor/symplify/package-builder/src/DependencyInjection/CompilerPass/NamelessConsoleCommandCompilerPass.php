<?php

declare (strict_types=1);
namespace MonorepoBuilder20211030\Symplify\PackageBuilder\DependencyInjection\CompilerPass;

use MonorepoBuilder20211030\Symfony\Component\Console\Command\Command;
use MonorepoBuilder20211030\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use MonorepoBuilder20211030\Symfony\Component\DependencyInjection\ContainerBuilder;
use MonorepoBuilder20211030\Symplify\PackageBuilder\Console\Command\CommandNaming;
/**
 * @see \Symplify\PackageBuilder\Tests\DependencyInjection\CompilerPass\NamelessConsoleCommandCompilerPassTest
 */
final class NamelessConsoleCommandCompilerPass implements \MonorepoBuilder20211030\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder
     */
    public function process($containerBuilder) : void
    {
        foreach ($containerBuilder->getDefinitions() as $definition) {
            $definitionClass = $definition->getClass();
            if ($definitionClass === null) {
                continue;
            }
            if (!\is_a($definitionClass, \MonorepoBuilder20211030\Symfony\Component\Console\Command\Command::class, \true)) {
                continue;
            }
            $commandName = \MonorepoBuilder20211030\Symplify\PackageBuilder\Console\Command\CommandNaming::classToName($definitionClass);
            $definition->addMethodCall('setName', [$commandName]);
        }
    }
}
