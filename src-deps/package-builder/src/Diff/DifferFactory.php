<?php

declare(strict_types=1);

namespace Symplify\PackageBuilder\Diff;

use SebastianBergmann\Diff\Differ;
use Symplify\PackageBuilder\Diff\Output\CompleteUnifiedDiffOutputBuilderFactory;
use Symplify\PackageBuilder\Reflection\PrivatesAccessor;

final class DifferFactory
{
    /**
     * @api
     */
    public function create(): Differ
    {
        $completeUnifiedDiffOutputBuilderFactory = new CompleteUnifiedDiffOutputBuilderFactory(
            new PrivatesAccessor()
        );

        $unifiedDiffOutputBuilder = $completeUnifiedDiffOutputBuilderFactory->create();

        return new Differ($unifiedDiffOutputBuilder);
    }
}
