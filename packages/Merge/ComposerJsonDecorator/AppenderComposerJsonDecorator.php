<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge\ComposerJsonDecorator;

use MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Merge\ComposerJsonMerger;
use Symplify\MonorepoBuilder\Merge\Configuration\ModifyingComposerJsonProvider;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerJsonDecoratorInterface;
/**
 * @see \Symplify\MonorepoBuilder\Tests\Merge\ComposerJsonDecorator\AppenderComposerJsonDecorator\AppenderComposerJsonDecoratorTest
 */
final class AppenderComposerJsonDecorator implements \Symplify\MonorepoBuilder\Merge\Contract\ComposerJsonDecoratorInterface
{
    /**
     * @var \Symplify\MonorepoBuilder\Merge\ComposerJsonMerger
     */
    private $composerJsonMerger;
    /**
     * @var \Symplify\MonorepoBuilder\Merge\Configuration\ModifyingComposerJsonProvider
     */
    private $modifyingComposerJsonProvider;
    public function __construct(\Symplify\MonorepoBuilder\Merge\ComposerJsonMerger $composerJsonMerger, \Symplify\MonorepoBuilder\Merge\Configuration\ModifyingComposerJsonProvider $modifyingComposerJsonProvider)
    {
        $this->composerJsonMerger = $composerJsonMerger;
        $this->modifyingComposerJsonProvider = $modifyingComposerJsonProvider;
    }
    public function decorate(\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $composerJson) : void
    {
        $appendingComposerJson = $this->modifyingComposerJsonProvider->getAppendingComposerJson();
        if (!$appendingComposerJson instanceof \MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson) {
            return;
        }
        $this->composerJsonMerger->mergeJsonToRoot($composerJson, $appendingComposerJson);
    }
}
