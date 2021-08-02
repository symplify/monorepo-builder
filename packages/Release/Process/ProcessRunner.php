<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Release\Process;

use MonorepoBuilder20210802\Symfony\Component\Console\Style\SymfonyStyle;
use MonorepoBuilder20210802\Symfony\Component\Process\Exception\ProcessFailedException;
use MonorepoBuilder20210802\Symfony\Component\Process\Process;
final class ProcessRunner
{
    /**
     * Reasonable timeout to report hang off: 10 minutes
     *
     * @var float
     */
    private const TIMEOUT = 10 * 60.0;
    /**
     * @var \Symfony\Component\Console\Style\SymfonyStyle
     */
    private $symfonyStyle;
    public function __construct(\MonorepoBuilder20210802\Symfony\Component\Console\Style\SymfonyStyle $symfonyStyle)
    {
        $this->symfonyStyle = $symfonyStyle;
    }
    /**
     * @param string|string[] $commandLine
     */
    public function run($commandLine) : string
    {
        if ($this->symfonyStyle->isVerbose()) {
            $this->symfonyStyle->note('Running process: ' . $this->normalizeToString($commandLine));
        }
        $process = $this->createProcess($commandLine);
        $process->run();
        $this->reportResult($process);
        return $process->getOutput();
    }
    /**
     * @param string|string[] $content
     */
    private function normalizeToString($content) : string
    {
        if (\is_array($content)) {
            return \implode(' ', $content);
        }
        return $content;
    }
    /**
     * @param string|string[] $commandLine
     */
    private function createProcess($commandLine) : \MonorepoBuilder20210802\Symfony\Component\Process\Process
    {
        // @since Symfony 4.2: https://github.com/symfony/symfony/pull/27821
        if (\is_string($commandLine) && \method_exists(\MonorepoBuilder20210802\Symfony\Component\Process\Process::class, 'fromShellCommandline')) {
            return \MonorepoBuilder20210802\Symfony\Component\Process\Process::fromShellCommandline($commandLine, null, null, null, self::TIMEOUT);
        }
        return new \MonorepoBuilder20210802\Symfony\Component\Process\Process($commandLine, null, null, null, self::TIMEOUT);
    }
    private function reportResult(\MonorepoBuilder20210802\Symfony\Component\Process\Process $process) : void
    {
        if ($process->isSuccessful()) {
            return;
        }
        throw new \MonorepoBuilder20210802\Symfony\Component\Process\Exception\ProcessFailedException($process);
    }
}
