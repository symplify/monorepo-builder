<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Console;

use MonorepoBuilder20211014\Symfony\Component\Console\Application;
use MonorepoBuilder20211014\Symfony\Component\Console\Command\Command;
use MonorepoBuilder20211014\Symplify\PackageBuilder\Console\Command\CommandNaming;
final class MonorepoBuilderConsoleApplication extends \MonorepoBuilder20211014\Symfony\Component\Console\Application
{
    /**
     * @param Command[] $commands
     */
    public function __construct(\MonorepoBuilder20211014\Symplify\PackageBuilder\Console\Command\CommandNaming $commandNaming, array $commands)
    {
        foreach ($commands as $command) {
            $commandName = $commandNaming->resolveFromCommand($command);
            $command->setName($commandName);
            $this->add($command);
        }
        parent::__construct('Monorepo Builder');
    }
}
