<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler;

use MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
/**
 * Compiler Pass Configuration.
 *
 * This class has a default configuration embedded.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class PassConfig
{
    public const TYPE_AFTER_REMOVING = 'afterRemoving';
    public const TYPE_BEFORE_OPTIMIZATION = 'beforeOptimization';
    public const TYPE_BEFORE_REMOVING = 'beforeRemoving';
    public const TYPE_OPTIMIZE = 'optimization';
    public const TYPE_REMOVE = 'removing';
    private $mergePass;
    /**
     * @var mixed[]
     */
    private $afterRemovingPasses;
    /**
     * @var mixed[]
     */
    private $beforeOptimizationPasses;
    /**
     * @var mixed[]
     */
    private $beforeRemovingPasses = [];
    /**
     * @var mixed[]
     */
    private $optimizationPasses;
    /**
     * @var mixed[]
     */
    private $removingPasses;
    public function __construct()
    {
        $this->mergePass = new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\MergeExtensionConfigurationPass();
        $this->beforeOptimizationPasses = [100 => [new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\ResolveClassPass(), new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\RegisterAutoconfigureAttributesPass(), new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\AttributeAutoconfigurationPass(), new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\ResolveInstanceofConditionalsPass(), new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\RegisterEnvVarProcessorsPass()], -1000 => [new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\ExtensionCompilerPass()]];
        $this->optimizationPasses = [[new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\AutoAliasServicePass(), new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\ValidateEnvPlaceholdersPass(), new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\ResolveDecoratorStackPass(), new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\ResolveChildDefinitionsPass(), new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\RegisterServiceSubscribersPass(), new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\ResolveParameterPlaceHoldersPass(\false, \false), new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\ResolveFactoryClassPass(), new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\ResolveNamedArgumentsPass(), new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\AutowireRequiredMethodsPass(), new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\AutowireRequiredPropertiesPass(), new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\ResolveBindingsPass(), new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass(), new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\DecoratorServicePass(), new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\CheckDefinitionValidityPass(), new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\AutowirePass(\false), new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass(), new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\ResolveTaggedIteratorArgumentPass(), new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\ResolveServiceSubscribersPass(), new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\ResolveReferencesToAliasesPass(), new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\ResolveInvalidReferencesPass(), new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\AnalyzeServiceReferencesPass(\true), new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\CheckCircularReferencesPass(), new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\CheckReferenceValidityPass(), new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\CheckArgumentsValidityPass(\false)]];
        $this->removingPasses = [[new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\RemovePrivateAliasesPass(), new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\ReplaceAliasByActualDefinitionPass(), new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\RemoveAbstractDefinitionsPass(), new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\RemoveUnusedDefinitionsPass(), new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\AnalyzeServiceReferencesPass(), new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\CheckExceptionOnInvalidReferenceBehaviorPass(), new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\InlineServiceDefinitionsPass(new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\AnalyzeServiceReferencesPass()), new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\AnalyzeServiceReferencesPass(), new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\DefinitionErrorExceptionPass()]];
        $this->afterRemovingPasses = [[new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\ResolveHotPathPass(), new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\ResolveNoPreloadPass(), new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\AliasDeprecatedPublicServicesPass()]];
    }
    /**
     * Returns all passes in order to be processed.
     *
     * @return CompilerPassInterface[]
     */
    public function getPasses() : array
    {
        return \array_merge([$this->mergePass], $this->getBeforeOptimizationPasses(), $this->getOptimizationPasses(), $this->getBeforeRemovingPasses(), $this->getRemovingPasses(), $this->getAfterRemovingPasses());
    }
    /**
     * Adds a pass.
     *
     * @throws InvalidArgumentException when a pass type doesn't exist
     */
    public function addPass(\MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface $pass, string $type = self::TYPE_BEFORE_OPTIMIZATION, int $priority = 0)
    {
        $property = $type . 'Passes';
        if (!isset($this->{$property})) {
            throw new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Exception\InvalidArgumentException(\sprintf('Invalid type "%s".', $type));
        }
        $passes =& $this->{$property};
        if (!isset($passes[$priority])) {
            $passes[$priority] = [];
        }
        $passes[$priority][] = $pass;
    }
    /**
     * Gets all passes for the AfterRemoving pass.
     *
     * @return CompilerPassInterface[]
     */
    public function getAfterRemovingPasses() : array
    {
        return $this->sortPasses($this->afterRemovingPasses);
    }
    /**
     * Gets all passes for the BeforeOptimization pass.
     *
     * @return CompilerPassInterface[]
     */
    public function getBeforeOptimizationPasses() : array
    {
        return $this->sortPasses($this->beforeOptimizationPasses);
    }
    /**
     * Gets all passes for the BeforeRemoving pass.
     *
     * @return CompilerPassInterface[]
     */
    public function getBeforeRemovingPasses() : array
    {
        return $this->sortPasses($this->beforeRemovingPasses);
    }
    /**
     * Gets all passes for the Optimization pass.
     *
     * @return CompilerPassInterface[]
     */
    public function getOptimizationPasses() : array
    {
        return $this->sortPasses($this->optimizationPasses);
    }
    /**
     * Gets all passes for the Removing pass.
     *
     * @return CompilerPassInterface[]
     */
    public function getRemovingPasses() : array
    {
        return $this->sortPasses($this->removingPasses);
    }
    /**
     * Gets the Merge pass.
     */
    public function getMergePass() : \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface
    {
        return $this->mergePass;
    }
    public function setMergePass(\MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface $pass)
    {
        $this->mergePass = $pass;
    }
    /**
     * Sets the AfterRemoving passes.
     *
     * @param CompilerPassInterface[] $passes
     */
    public function setAfterRemovingPasses(array $passes)
    {
        $this->afterRemovingPasses = [$passes];
    }
    /**
     * Sets the BeforeOptimization passes.
     *
     * @param CompilerPassInterface[] $passes
     */
    public function setBeforeOptimizationPasses(array $passes)
    {
        $this->beforeOptimizationPasses = [$passes];
    }
    /**
     * Sets the BeforeRemoving passes.
     *
     * @param CompilerPassInterface[] $passes
     */
    public function setBeforeRemovingPasses(array $passes)
    {
        $this->beforeRemovingPasses = [$passes];
    }
    /**
     * Sets the Optimization passes.
     *
     * @param CompilerPassInterface[] $passes
     */
    public function setOptimizationPasses(array $passes)
    {
        $this->optimizationPasses = [$passes];
    }
    /**
     * Sets the Removing passes.
     *
     * @param CompilerPassInterface[] $passes
     */
    public function setRemovingPasses(array $passes)
    {
        $this->removingPasses = [$passes];
    }
    /**
     * Sort passes by priority.
     *
     * @param array $passes CompilerPassInterface instances with their priority as key
     *
     * @return CompilerPassInterface[]
     */
    private function sortPasses(array $passes) : array
    {
        if (0 === \count($passes)) {
            return [];
        }
        \krsort($passes);
        // Flatten the array
        return \array_merge(...$passes);
    }
}
