<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge\ComposerJsonDecorator;

use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Merge\Configuration\ModifyingComposerJsonProvider;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerJsonDecoratorInterface;
use Symplify\MonorepoBuilder\ValueObject\Option;
final class RemoverComposerJsonDecorator implements ComposerJsonDecoratorInterface
{
    /**
     * @var \Symplify\MonorepoBuilder\Merge\Configuration\ModifyingComposerJsonProvider
     */
    private $modifyingComposerJsonProvider;
    public function __construct(ModifyingComposerJsonProvider $modifyingComposerJsonProvider)
    {
        $this->modifyingComposerJsonProvider = $modifyingComposerJsonProvider;
    }
    public function decorate(ComposerJson $composerJson) : void
    {
        $removingComposerJson = $this->modifyingComposerJsonProvider->getRemovingComposerJson();
        if (!$removingComposerJson instanceof ComposerJson) {
            return;
        }
        $this->processRequire($composerJson, $removingComposerJson);
        $this->processRequireDev($composerJson, $removingComposerJson);
        $this->processAutoload($composerJson, $removingComposerJson);
        $this->processAutoloadDev($composerJson, $removingComposerJson);
        $this->processRoot($composerJson, $removingComposerJson);
    }
    private function processRequire(ComposerJson $composerJson, ComposerJson $composerJsonToRemove) : void
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
    private function processRequireDev(ComposerJson $composerJson, ComposerJson $composerJsonToRemove) : void
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
    private function processAutoload(ComposerJson $composerJson, ComposerJson $composerJsonToRemove) : void
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
    private function processAutoloadDev(ComposerJson $composerJson, ComposerJson $composerJsonToRemove) : void
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
    private function processRoot(ComposerJson $composerJson, ComposerJson $removingComposerJson) : void
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
        if ($removingComposerJson->getRepositories()[0] !== Option::REMOVE_COMPLETELY) {
            return;
        }
        $composerJson->setRepositories([]);
    }
}
