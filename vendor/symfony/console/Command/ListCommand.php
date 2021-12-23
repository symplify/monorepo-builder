<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilder20211223\Symfony\Component\Console\Command;

use MonorepoBuilder20211223\Symfony\Component\Console\Completion\CompletionInput;
use MonorepoBuilder20211223\Symfony\Component\Console\Completion\CompletionSuggestions;
use MonorepoBuilder20211223\Symfony\Component\Console\Descriptor\ApplicationDescription;
use MonorepoBuilder20211223\Symfony\Component\Console\Helper\DescriptorHelper;
use MonorepoBuilder20211223\Symfony\Component\Console\Input\InputArgument;
use MonorepoBuilder20211223\Symfony\Component\Console\Input\InputInterface;
use MonorepoBuilder20211223\Symfony\Component\Console\Input\InputOption;
use MonorepoBuilder20211223\Symfony\Component\Console\Output\OutputInterface;
/**
 * ListCommand displays the list of all available commands for the application.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class ListCommand extends \MonorepoBuilder20211223\Symfony\Component\Console\Command\Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('list')->setDefinition([new \MonorepoBuilder20211223\Symfony\Component\Console\Input\InputArgument('namespace', \MonorepoBuilder20211223\Symfony\Component\Console\Input\InputArgument::OPTIONAL, 'The namespace name'), new \MonorepoBuilder20211223\Symfony\Component\Console\Input\InputOption('raw', null, \MonorepoBuilder20211223\Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'To output raw command list'), new \MonorepoBuilder20211223\Symfony\Component\Console\Input\InputOption('format', null, \MonorepoBuilder20211223\Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED, 'The output format (txt, xml, json, or md)', 'txt'), new \MonorepoBuilder20211223\Symfony\Component\Console\Input\InputOption('short', null, \MonorepoBuilder20211223\Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'To skip describing commands\' arguments')])->setDescription('List commands')->setHelp(<<<'EOF'
The <info>%command.name%</info> command lists all commands:

  <info>%command.full_name%</info>

You can also display the commands for a specific namespace:

  <info>%command.full_name% test</info>

You can also output the information in other formats by using the <comment>--format</comment> option:

  <info>%command.full_name% --format=xml</info>

It's also possible to get raw list of commands (useful for embedding command runner):

  <info>%command.full_name% --raw</info>
EOF
);
    }
    /**
     * {@inheritdoc}
     */
    protected function execute(\MonorepoBuilder20211223\Symfony\Component\Console\Input\InputInterface $input, \MonorepoBuilder20211223\Symfony\Component\Console\Output\OutputInterface $output) : int
    {
        $helper = new \MonorepoBuilder20211223\Symfony\Component\Console\Helper\DescriptorHelper();
        $helper->describe($output, $this->getApplication(), ['format' => $input->getOption('format'), 'raw_text' => $input->getOption('raw'), 'namespace' => $input->getArgument('namespace'), 'short' => $input->getOption('short')]);
        return 0;
    }
    public function complete(\MonorepoBuilder20211223\Symfony\Component\Console\Completion\CompletionInput $input, \MonorepoBuilder20211223\Symfony\Component\Console\Completion\CompletionSuggestions $suggestions) : void
    {
        if ($input->mustSuggestArgumentValuesFor('namespace')) {
            $descriptor = new \MonorepoBuilder20211223\Symfony\Component\Console\Descriptor\ApplicationDescription($this->getApplication());
            $suggestions->suggestValues(\array_keys($descriptor->getNamespaces()));
            return;
        }
        if ($input->mustSuggestOptionValuesFor('format')) {
            $helper = new \MonorepoBuilder20211223\Symfony\Component\Console\Helper\DescriptorHelper();
            $suggestions->suggestValues($helper->getFormats());
        }
    }
}
