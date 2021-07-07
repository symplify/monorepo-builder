<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilder20210707\Symfony\Component\Console\Input;

use MonorepoBuilder20210707\Symfony\Component\Console\Exception\InvalidArgumentException;
use MonorepoBuilder20210707\Symfony\Component\Console\Exception\RuntimeException;
/**
 * Input is the base class for all concrete Input classes.
 *
 * Three concrete classes are provided by default:
 *
 *  * `ArgvInput`: The input comes from the CLI arguments (argv)
 *  * `StringInput`: The input is provided as a string
 *  * `ArrayInput`: The input is provided as an array
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
abstract class Input implements \MonorepoBuilder20210707\Symfony\Component\Console\Input\InputInterface, \MonorepoBuilder20210707\Symfony\Component\Console\Input\StreamableInputInterface
{
    protected $definition;
    protected $stream;
    protected $options = [];
    protected $arguments = [];
    protected $interactive = \true;
    public function __construct(\MonorepoBuilder20210707\Symfony\Component\Console\Input\InputDefinition $definition = null)
    {
        if (null === $definition) {
            $this->definition = new \MonorepoBuilder20210707\Symfony\Component\Console\Input\InputDefinition();
        } else {
            $this->bind($definition);
            $this->validate();
        }
    }
    /**
     * {@inheritdoc}
     */
    public function bind(\MonorepoBuilder20210707\Symfony\Component\Console\Input\InputDefinition $definition)
    {
        $this->arguments = [];
        $this->options = [];
        $this->definition = $definition;
        $this->parse();
    }
    /**
     * Processes command line arguments.
     */
    protected abstract function parse();
    /**
     * {@inheritdoc}
     */
    public function validate()
    {
        $definition = $this->definition;
        $givenArguments = $this->arguments;
        $missingArguments = \array_filter(\array_keys($definition->getArguments()), function ($argument) use($definition, $givenArguments) {
            return !\array_key_exists($argument, $givenArguments) && $definition->getArgument($argument)->isRequired();
        });
        if (\count($missingArguments) > 0) {
            throw new \MonorepoBuilder20210707\Symfony\Component\Console\Exception\RuntimeException(\sprintf('Not enough arguments (missing: "%s").', \implode(', ', $missingArguments)));
        }
    }
    /**
     * {@inheritdoc}
     */
    public function isInteractive()
    {
        return $this->interactive;
    }
    /**
     * {@inheritdoc}
     */
    public function setInteractive(bool $interactive)
    {
        $this->interactive = $interactive;
    }
    /**
     * {@inheritdoc}
     */
    public function getArguments()
    {
        return \array_merge($this->definition->getArgumentDefaults(), $this->arguments);
    }
    /**
     * {@inheritdoc}
     */
    public function getArgument(string $name)
    {
        if (!$this->definition->hasArgument($name)) {
            throw new \MonorepoBuilder20210707\Symfony\Component\Console\Exception\InvalidArgumentException(\sprintf('The "%s" argument does not exist.', $name));
        }
        return $this->arguments[$name] ?? $this->definition->getArgument($name)->getDefault();
    }
    /**
     * {@inheritdoc}
     */
    public function setArgument(string $name, $value)
    {
        if (!$this->definition->hasArgument($name)) {
            throw new \MonorepoBuilder20210707\Symfony\Component\Console\Exception\InvalidArgumentException(\sprintf('The "%s" argument does not exist.', $name));
        }
        $this->arguments[$name] = $value;
    }
    /**
     * {@inheritdoc}
     */
    public function hasArgument($name)
    {
        return $this->definition->hasArgument($name);
    }
    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return \array_merge($this->definition->getOptionDefaults(), $this->options);
    }
    /**
     * {@inheritdoc}
     */
    public function getOption(string $name)
    {
        if ($this->definition->hasNegation($name)) {
            if (null === ($value = $this->getOption($this->definition->negationToName($name)))) {
                return $value;
            }
            return !$value;
        }
        if (!$this->definition->hasOption($name)) {
            throw new \MonorepoBuilder20210707\Symfony\Component\Console\Exception\InvalidArgumentException(\sprintf('The "%s" option does not exist.', $name));
        }
        return \array_key_exists($name, $this->options) ? $this->options[$name] : $this->definition->getOption($name)->getDefault();
    }
    /**
     * {@inheritdoc}
     */
    public function setOption(string $name, $value)
    {
        if ($this->definition->hasNegation($name)) {
            $this->options[$this->definition->negationToName($name)] = !$value;
            return;
        } elseif (!$this->definition->hasOption($name)) {
            throw new \MonorepoBuilder20210707\Symfony\Component\Console\Exception\InvalidArgumentException(\sprintf('The "%s" option does not exist.', $name));
        }
        $this->options[$name] = $value;
    }
    /**
     * {@inheritdoc}
     */
    public function hasOption(string $name)
    {
        return $this->definition->hasOption($name) || $this->definition->hasNegation($name);
    }
    /**
     * Escapes a token through escapeshellarg if it contains unsafe chars.
     *
     * @return string
     */
    public function escapeToken(string $token)
    {
        return \preg_match('{^[\\w-]+$}', $token) ? $token : \escapeshellarg($token);
    }
    /**
     * {@inheritdoc}
     */
    public function setStream($stream)
    {
        $this->stream = $stream;
    }
    /**
     * {@inheritdoc}
     */
    public function getStream()
    {
        return $this->stream;
    }
}