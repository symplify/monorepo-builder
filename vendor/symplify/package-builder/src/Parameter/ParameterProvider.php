<?php

declare (strict_types=1);
namespace MonorepoBuilderPrefix202311\Symplify\PackageBuilder\Parameter;

use MonorepoBuilderPrefix202311\Symfony\Component\DependencyInjection\ContainerInterface;
use MonorepoBuilderPrefix202311\Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use MonorepoBuilderPrefix202311\Symfony\Component\HttpFoundation\ParameterBag;
/**
 * @api
 * @see \Symplify\PackageBuilder\Tests\Parameter\ParameterProviderTest
 */
final class ParameterProvider
{
    /**
     * @var array<string, mixed>
     */
    private $parameters = [];
    public function __construct(ContainerInterface $container)
    {
        /** @var ParameterBag $parameterBag */
        $parameterBag = $container->getParameterBag();
        $this->parameters = $parameterBag->all();
    }
    public function hasParameter(string $name) : bool
    {
        return isset($this->parameters[$name]);
    }
    /**
     * @api
     * @return mixed
     */
    public function provideParameter(string $name)
    {
        return $this->parameters[$name] ?? null;
    }
    /**
     * @api
     */
    public function provideStringParameter(string $name) : string
    {
        $this->ensureParameterIsSet($name);
        return (string) $this->parameters[$name];
    }
    /**
     * @api
     * @return mixed[]
     */
    public function provideArrayParameter(string $name) : array
    {
        $this->ensureParameterIsSet($name);
        return $this->parameters[$name];
    }
    /**
     * @api
     */
    public function provideBoolParameter(string $parameterName) : bool
    {
        return $this->parameters[$parameterName] ?? \false;
    }
    /**
     * @param mixed $value
     */
    public function changeParameter(string $name, $value) : void
    {
        $this->parameters[$name] = $value;
    }
    /**
     * @api
     * @return mixed[]
     */
    public function provide() : array
    {
        return $this->parameters;
    }
    /**
     * @api
     */
    public function provideIntParameter(string $name) : int
    {
        $this->ensureParameterIsSet($name);
        return (int) $this->parameters[$name];
    }
    /**
     * @api
     */
    public function ensureParameterIsSet(string $name) : void
    {
        if (\array_key_exists($name, $this->parameters)) {
            return;
        }
        throw new ParameterNotFoundException($name);
    }
}
