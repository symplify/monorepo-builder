<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Release\Output;

use MonorepoBuilder20211223\Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\StageAwareInterface;
final class ReleaseWorkerReporter
{
    /**
     * @var \Symfony\Component\Console\Style\SymfonyStyle
     */
    private $symfonyStyle;
    public function __construct(\MonorepoBuilder20211223\Symfony\Component\Console\Style\SymfonyStyle $symfonyStyle)
    {
        $this->symfonyStyle = $symfonyStyle;
    }
    public function printMetadata(\Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface $releaseWorker) : void
    {
        if (!$this->symfonyStyle->isVerbose()) {
            return;
        }
        // show debug data on -v/--verbose/--debug
        $this->symfonyStyle->writeln('class: ' . \get_class($releaseWorker));
        if ($releaseWorker instanceof \Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\StageAwareInterface) {
            $this->symfonyStyle->writeln('stage: ' . $releaseWorker->getStage());
        }
        $this->symfonyStyle->newLine();
    }
}
