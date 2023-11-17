<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilderPrefix202311\Symfony\Component\Config\Resource;

/**
 * ComposerResource tracks the PHP version and Composer dependencies.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 *
 * @final
 */
class ComposerResource implements SelfCheckingResourceInterface
{
    /**
     * @var mixed[]
     */
    private $vendors;
    /**
     * @var mixed[]
     */
    private static $runtimeVendors;
    public function __construct()
    {
        self::refresh();
        $this->vendors = self::$runtimeVendors;
    }
    public function getVendors() : array
    {
        return \array_keys($this->vendors);
    }
    public function __toString() : string
    {
        return __CLASS__;
    }
    public function isFresh(int $timestamp) : bool
    {
        self::refresh();
        return \array_values(self::$runtimeVendors) === \array_values($this->vendors);
    }
    private static function refresh() : void
    {
        self::$runtimeVendors = [];
        foreach (\get_declared_classes() as $class) {
            if ('C' === $class[0] && \strncmp($class, 'ComposerAutoloaderInit', \strlen('ComposerAutoloaderInit')) === 0) {
                $r = new \ReflectionClass($class);
                $v = \dirname($r->getFileName(), 2);
                if (\is_file($v . '/composer/installed.json')) {
                    self::$runtimeVendors[$v] = @\filemtime($v . '/composer/installed.json');
                }
            }
        }
    }
}
