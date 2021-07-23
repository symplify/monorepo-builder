<?php

declare (strict_types=1);
namespace MonorepoBuilder20210723\Symplify\EasyTesting\Console;

use MonorepoBuilder20210723\Symfony\Component\Console\Application;
use MonorepoBuilder20210723\Symfony\Component\Console\Command\Command;
use MonorepoBuilder20210723\Symplify\PackageBuilder\Console\Command\CommandNaming;
final class EasyTestingConsoleApplication extends \MonorepoBuilder20210723\Symfony\Component\Console\Application
{
    /**
     * @param Command[] $commands
     */
    public function __construct(\MonorepoBuilder20210723\Symplify\PackageBuilder\Console\Command\CommandNaming $commandNaming, array $commands)
    {
        foreach ($commands as $command) {
            $commandName = $commandNaming->resolveFromCommand($command);
            $command->setName($commandName);
            $this->add($command);
        }
        parent::__construct('Easy Testing');
    }
}
