<?php

declare (strict_types=1);
namespace MonorepoBuilder20211223\Symplify\ConsoleColorDiff\Console\Output;

use MonorepoBuilder20211223\SebastianBergmann\Diff\Differ;
use MonorepoBuilder20211223\Symplify\ConsoleColorDiff\Console\Formatter\ColorConsoleDiffFormatter;
/**
 * @api
 */
final class ConsoleDiffer
{
    /**
     * @var \SebastianBergmann\Diff\Differ
     */
    private $differ;
    /**
     * @var \Symplify\ConsoleColorDiff\Console\Formatter\ColorConsoleDiffFormatter
     */
    private $colorConsoleDiffFormatter;
    public function __construct(\MonorepoBuilder20211223\SebastianBergmann\Diff\Differ $differ, \MonorepoBuilder20211223\Symplify\ConsoleColorDiff\Console\Formatter\ColorConsoleDiffFormatter $colorConsoleDiffFormatter)
    {
        $this->differ = $differ;
        $this->colorConsoleDiffFormatter = $colorConsoleDiffFormatter;
    }
    public function diff(string $old, string $new) : string
    {
        $diff = $this->differ->diff($old, $new);
        return $this->colorConsoleDiffFormatter->format($diff);
    }
}
