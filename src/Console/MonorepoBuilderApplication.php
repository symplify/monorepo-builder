<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Console;

use MonorepoBuilder20220610\Symfony\Component\Console\Application;
use MonorepoBuilder20220610\Symfony\Component\Console\Command\Command;
final class MonorepoBuilderApplication extends Application
{
    /**
     * @param Command[] $commands
     */
    public function __construct(array $commands)
    {
        $this->addCommands($commands);
        parent::__construct();
    }
}
