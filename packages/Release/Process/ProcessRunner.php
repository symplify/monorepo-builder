<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Release\Process;

use MonorepoBuilderPrefix202311\Symfony\Component\Console\Style\SymfonyStyle;
use MonorepoBuilderPrefix202311\Symfony\Component\Process\Exception\ProcessFailedException;
use MonorepoBuilderPrefix202311\Symfony\Component\Process\Process;
final class ProcessRunner
{
    /**
     * @var \Symfony\Component\Console\Style\SymfonyStyle
     */
    private $symfonyStyle;
    /**
     * Reasonable timeout to report hang off: 10 minutes
     *
     * @var float
     */
    private const TIMEOUT = 10 * 60.0;
    public function __construct(SymfonyStyle $symfonyStyle)
    {
        $this->symfonyStyle = $symfonyStyle;
    }
    /**
     * @param string|string[] $commandLine
     */
    public function run($commandLine, ?string $cwd = null) : string
    {
        if ($this->symfonyStyle->isVerbose()) {
            $this->symfonyStyle->note('Running process: ' . $this->normalizeToString($commandLine));
        }
        $process = $this->createProcess($commandLine, $cwd);
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
    private function createProcess($commandLine, ?string $cwd) : Process
    {
        // @since Symfony 4.2: https://github.com/symfony/symfony/pull/27821
        if (\is_string($commandLine) && \method_exists(Process::class, 'fromShellCommandline')) {
            return Process::fromShellCommandline($commandLine, $cwd, null, null, self::TIMEOUT);
        }
        return new Process($commandLine, $cwd, null, null, self::TIMEOUT);
    }
    private function reportResult(Process $process) : void
    {
        if ($process->isSuccessful()) {
            return;
        }
        throw new ProcessFailedException($process);
    }
}
