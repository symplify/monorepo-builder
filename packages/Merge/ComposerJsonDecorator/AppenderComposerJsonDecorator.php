<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge\ComposerJsonDecorator;

use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Merge\ComposerJsonMerger;
use Symplify\MonorepoBuilder\Merge\Configuration\ModifyingComposerJsonProvider;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerJsonDecoratorInterface;
final class AppenderComposerJsonDecorator implements ComposerJsonDecoratorInterface
{
    /**
     * @var \Symplify\MonorepoBuilder\Merge\ComposerJsonMerger
     */
    private $composerJsonMerger;
    /**
     * @var \Symplify\MonorepoBuilder\Merge\Configuration\ModifyingComposerJsonProvider
     */
    private $modifyingComposerJsonProvider;
    public function __construct(ComposerJsonMerger $composerJsonMerger, ModifyingComposerJsonProvider $modifyingComposerJsonProvider)
    {
        $this->composerJsonMerger = $composerJsonMerger;
        $this->modifyingComposerJsonProvider = $modifyingComposerJsonProvider;
    }
    public function decorate(ComposerJson $composerJson) : void
    {
        $appendingComposerJson = $this->modifyingComposerJsonProvider->getAppendingComposerJson();
        if (!$appendingComposerJson instanceof ComposerJson) {
            return;
        }
        $this->composerJsonMerger->mergeJsonToRoot($composerJson, $appendingComposerJson);
    }
}
