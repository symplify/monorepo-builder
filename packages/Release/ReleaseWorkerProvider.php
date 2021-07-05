<?php

declare (strict_types=1);
namespace MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release;

use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\StageAwareInterface;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\ValueObject\Stage;
/**
 * @see \Symplify\MonorepoBuilder\Tests\Release\ReleaseWorkerProvider\ReleaseWorkerProviderTest
 */
final class ReleaseWorkerProvider
{
    /**
     * @var ReleaseWorkerInterface[]
     */
    private $releaseWorkers = [];
    /**
     * @param ReleaseWorkerInterface[] $releaseWorkers
     */
    public function __construct(array $releaseWorkers)
    {
        $this->releaseWorkers = $releaseWorkers;
    }
    /**
     * @return ReleaseWorkerInterface[]
     */
    public function provide() : array
    {
        return $this->releaseWorkers;
    }
    /**
     * @return ReleaseWorkerInterface[]|StageAwareInterface[]
     */
    public function provideByStage(string $stage) : array
    {
        if ($stage === \MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\ValueObject\Stage::MAIN) {
            return $this->releaseWorkers;
        }
        $activeReleaseWorkers = [];
        foreach ($this->releaseWorkers as $releaseWorker) {
            if (!$releaseWorker instanceof \MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\StageAwareInterface) {
                continue;
            }
            if ($stage !== $releaseWorker->getStage()) {
                continue;
            }
            $activeReleaseWorkers[] = $releaseWorker;
        }
        return $activeReleaseWorkers;
    }
}
