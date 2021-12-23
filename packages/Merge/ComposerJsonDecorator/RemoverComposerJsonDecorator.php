<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge\ComposerJsonDecorator;

use MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Merge\Configuration\ModifyingComposerJsonProvider;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerJsonDecoratorInterface;
use Symplify\MonorepoBuilder\ValueObject\Option;
/**
 * @see \Symplify\MonorepoBuilder\Tests\Merge\ComposerJsonDecorator\RemoverComposerJsonDecoratorTest
 * @see \Symplify\MonorepoBuilder\Tests\Merge\ComposerJsonDecorator\RemoverComposerJsonDecorator\RemoverComposerJsonDecoratorTest
 */
final class RemoverComposerJsonDecorator implements \Symplify\MonorepoBuilder\Merge\Contract\ComposerJsonDecoratorInterface
{
    /**
     * @var \Symplify\MonorepoBuilder\Merge\Configuration\ModifyingComposerJsonProvider
     */
    private $modifyingComposerJsonProvider;
    public function __construct(\Symplify\MonorepoBuilder\Merge\Configuration\ModifyingComposerJsonProvider $modifyingComposerJsonProvider)
    {
        $this->modifyingComposerJsonProvider = $modifyingComposerJsonProvider;
    }
    public function decorate(\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $composerJson) : void
    {
        $removingComposerJson = $this->modifyingComposerJsonProvider->getRemovingComposerJson();
        if (!$removingComposerJson instanceof \MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson) {
            return;
        }
        $this->processRequire($composerJson, $removingComposerJson);
        $this->processRequireDev($composerJson, $removingComposerJson);
        $this->processAutoload($composerJson, $removingComposerJson);
        $this->processAutoloadDev($composerJson, $removingComposerJson);
        $this->processRoot($composerJson, $removingComposerJson);
    }
    private function processRequire(\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $composerJson, \MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $composerJsonToRemove) : void
    {
        if ($composerJsonToRemove->getRequire() === []) {
            return;
        }
        $currentRequire = $composerJson->getRequire();
        $packages = \array_keys($composerJsonToRemove->getRequire());
        foreach ($packages as $package) {
            unset($currentRequire[$package]);
        }
        $composerJson->setRequire($currentRequire);
    }
    private function processRequireDev(\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $composerJson, \MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $composerJsonToRemove) : void
    {
        if ($composerJsonToRemove->getRequireDev() === []) {
            return;
        }
        $currentRequireDev = $composerJson->getRequireDev();
        $packages = \array_keys($composerJsonToRemove->getRequireDev());
        foreach ($packages as $package) {
            unset($currentRequireDev[$package]);
        }
        $composerJson->setRequireDev($currentRequireDev);
    }
    private function processAutoload(\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $composerJson, \MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $composerJsonToRemove) : void
    {
        if ($composerJsonToRemove->getAutoload() === []) {
            return;
        }
        $currentAutoload = $composerJson->getAutoload();
        $autoloads = $composerJsonToRemove->getAutoload();
        foreach ($autoloads as $type => $autoloadList) {
            if (!\is_array($autoloadList)) {
                continue;
            }
            $namespaces = \array_keys($autoloadList);
            foreach ($namespaces as $namespace) {
                unset($currentAutoload[$type][$namespace]);
            }
        }
        $composerJson->setAutoload($currentAutoload);
    }
    private function processAutoloadDev(\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $composerJson, \MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $composerJsonToRemove) : void
    {
        if ($composerJsonToRemove->getAutoloadDev() === []) {
            return;
        }
        $currentAutoloadDev = $composerJson->getAutoloadDev();
        $autoloadDev = $composerJsonToRemove->getAutoloadDev();
        foreach ($autoloadDev as $type => $autoloadList) {
            if (!\is_array($autoloadList)) {
                continue;
            }
            $namespaces = \array_keys($autoloadList);
            foreach ($namespaces as $namespace) {
                unset($currentAutoloadDev[$type][$namespace]);
            }
        }
        $composerJson->setAutoloadDev($currentAutoloadDev);
    }
    private function processRoot(\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $composerJson, \MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $removingComposerJson) : void
    {
        if ($removingComposerJson->getMinimumStability()) {
            $composerJson->removeMinimumStability();
        }
        if ($removingComposerJson->getPreferStable()) {
            $composerJson->removePreferStable();
        }
        if (\count($removingComposerJson->getRepositories()) !== 1) {
            return;
        }
        if ($removingComposerJson->getRepositories()[0] !== \Symplify\MonorepoBuilder\ValueObject\Option::REMOVE_COMPLETELY) {
            return;
        }
        $composerJson->setRepositories([]);
    }
}
