<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilder20210913\Symfony\Component\HttpKernel\DataCollector;

use MonorepoBuilder20210913\Symfony\Component\VarDumper\Caster\CutStub;
use MonorepoBuilder20210913\Symfony\Component\VarDumper\Caster\ReflectionCaster;
use MonorepoBuilder20210913\Symfony\Component\VarDumper\Cloner\ClonerInterface;
use MonorepoBuilder20210913\Symfony\Component\VarDumper\Cloner\Data;
use MonorepoBuilder20210913\Symfony\Component\VarDumper\Cloner\Stub;
use MonorepoBuilder20210913\Symfony\Component\VarDumper\Cloner\VarCloner;
/**
 * DataCollector.
 *
 * Children of this class must store the collected data in the data property.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Bernhard Schussek <bschussek@symfony.com>
 */
abstract class DataCollector implements \MonorepoBuilder20210913\Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface
{
    /**
     * @var array|Data
     */
    protected $data = [];
    /**
     * @var ClonerInterface
     */
    private $cloner;
    /**
     * Converts the variable into a serializable Data instance.
     *
     * This array can be displayed in the template using
     * the VarDumper component.
     *
     * @param mixed $var
     *
     * @return Data
     */
    protected function cloneVar($var)
    {
        if ($var instanceof \MonorepoBuilder20210913\Symfony\Component\VarDumper\Cloner\Data) {
            return $var;
        }
        if (null === $this->cloner) {
            $this->cloner = new \MonorepoBuilder20210913\Symfony\Component\VarDumper\Cloner\VarCloner();
            $this->cloner->setMaxItems(-1);
            $this->cloner->addCasters($this->getCasters());
        }
        return $this->cloner->cloneVar($var);
    }
    /**
     * @return callable[] The casters to add to the cloner
     */
    protected function getCasters()
    {
        $casters = ['*' => function ($v, array $a, \MonorepoBuilder20210913\Symfony\Component\VarDumper\Cloner\Stub $s, $isNested) {
            if (!$v instanceof \MonorepoBuilder20210913\Symfony\Component\VarDumper\Cloner\Stub) {
                foreach ($a as $k => $v) {
                    if (\is_object($v) && !$v instanceof \DateTimeInterface && !$v instanceof \MonorepoBuilder20210913\Symfony\Component\VarDumper\Cloner\Stub) {
                        $a[$k] = new \MonorepoBuilder20210913\Symfony\Component\VarDumper\Caster\CutStub($v);
                    }
                }
            }
            return $a;
        }] + \MonorepoBuilder20210913\Symfony\Component\VarDumper\Caster\ReflectionCaster::UNSET_CLOSURE_FILE_INFO;
        return $casters;
    }
    /**
     * @return array
     */
    public function __sleep()
    {
        return ['data'];
    }
    public function __wakeup()
    {
    }
    /**
     * @internal to prevent implementing \Serializable
     */
    protected final function serialize()
    {
    }
    /**
     * @internal to prevent implementing \Serializable
     */
    protected final function unserialize($data)
    {
    }
}
