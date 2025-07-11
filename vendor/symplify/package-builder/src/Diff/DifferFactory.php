<?php

declare (strict_types=1);
namespace MonorepoBuilderPrefix202507\Symplify\PackageBuilder\Diff;

use MonorepoBuilderPrefix202507\SebastianBergmann\Diff\Differ;
use MonorepoBuilderPrefix202507\Symplify\PackageBuilder\Diff\Output\CompleteUnifiedDiffOutputBuilderFactory;
use MonorepoBuilderPrefix202507\Symplify\PackageBuilder\Reflection\PrivatesAccessor;
final class DifferFactory
{
    /**
     * @api
     */
    public function create() : Differ
    {
        $completeUnifiedDiffOutputBuilderFactory = new CompleteUnifiedDiffOutputBuilderFactory(new PrivatesAccessor());
        $unifiedDiffOutputBuilder = $completeUnifiedDiffOutputBuilderFactory->create();
        return new Differ($unifiedDiffOutputBuilder);
    }
}
