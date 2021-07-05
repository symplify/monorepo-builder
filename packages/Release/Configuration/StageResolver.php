<?php

declare (strict_types=1);
namespace MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\Configuration;

use MonorepoBuilder20210705\Symfony\Component\Console\Input\InputInterface;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\Guard\ReleaseGuard;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\ValueObject\Stage;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\ValueObject\Option;
final class StageResolver
{
    /**
     * @var \Symplify\MonorepoBuilder\Release\Guard\ReleaseGuard
     */
    private $releaseGuard;
    public function __construct(\MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\Guard\ReleaseGuard $releaseGuard)
    {
        $this->releaseGuard = $releaseGuard;
    }
    public function resolveFromInput(\MonorepoBuilder20210705\Symfony\Component\Console\Input\InputInterface $input) : string
    {
        $stage = (string) $input->getOption(\MonorepoBuilder20210705\Symplify\MonorepoBuilder\ValueObject\Option::STAGE);
        // empty
        if ($stage === \MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\ValueObject\Stage::MAIN) {
            $this->releaseGuard->guardRequiredStageOnEmptyStage();
        } else {
            $this->releaseGuard->guardStage($stage);
        }
        return $stage;
    }
}
