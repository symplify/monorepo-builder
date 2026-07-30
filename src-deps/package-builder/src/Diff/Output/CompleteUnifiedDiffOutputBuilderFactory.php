<?php

declare(strict_types=1);

namespace Symplify\PackageBuilder\Diff\Output;

use SebastianBergmann\Diff\Output\DiffOutputBuilderInterface;
use SebastianBergmann\Diff\Output\StrictUnifiedDiffOutputBuilder;
use SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;
use Symplify\PackageBuilder\Reflection\PrivatesAccessor;

/**
 * @api
 * Creates a diff output builder with a very large context so full files are shown.
 */
final readonly class CompleteUnifiedDiffOutputBuilderFactory
{
    /**
     * @var int
     */
    private const CONTEXT_LINES = 10000;

    public function __construct(
        private PrivatesAccessor $privatesAccessor
    ) {
    }

    /**
     * @api
     */
    public function create(): DiffOutputBuilderInterface
    {
        // sebastian/diff < 9 shipped UnifiedDiffOutputBuilder with a private
        // "contextLines" property that had to be overridden via reflection.
        if (class_exists(UnifiedDiffOutputBuilder::class)) {
            $unifiedDiffOutputBuilder = new UnifiedDiffOutputBuilder('');
            $this->privatesAccessor->setPrivateProperty(
                $unifiedDiffOutputBuilder,
                'contextLines',
                self::CONTEXT_LINES
            );

            return $unifiedDiffOutputBuilder;
        }

        // sebastian/diff >= 9 removed UnifiedDiffOutputBuilder and exposes
        // "contextLines" as a public constructor option instead.
        return new StrictUnifiedDiffOutputBuilder([
            'contextLines' => self::CONTEXT_LINES,
            'header' => '',
        ]);
    }
}
