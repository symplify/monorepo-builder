<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Tests\Merge\PathResolver;

use Symplify\MonorepoBuilder\Merge\PathResolver\ComposerPatchesPathNormalizer;
use Symplify\MonorepoBuilder\Tests\Merge\ComposerJsonDecorator\AbstractComposerJsonDecorator;

final class ComposerPatchesPathNormalizer extends AbstractComposerJsonDecorator
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test(): void
    {
        if (! defined('SYMPLIFY_MONOREPO')) {
            $this->markTestSkipped('Already tested on monorepo');
        }
    }
}
