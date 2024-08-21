<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Console;

use MonorepoBuilderPrefix202408\Symfony\Component\Console\Application;
use MonorepoBuilderPrefix202408\Symfony\Component\Console\Command\Command;
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
