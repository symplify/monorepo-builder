<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilder20210913\Symfony\Component\HttpFoundation\Session\Storage;

use MonorepoBuilder20210913\Symfony\Component\HttpFoundation\Request;
// Help opcache.preload discover always-needed symbols
\class_exists(\MonorepoBuilder20210913\Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage::class);
/**
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
class MockFileSessionStorageFactory implements \MonorepoBuilder20210913\Symfony\Component\HttpFoundation\Session\Storage\SessionStorageFactoryInterface
{
    private $savePath;
    private $name;
    private $metaBag;
    /**
     * @see MockFileSessionStorage constructor.
     */
    public function __construct(string $savePath = null, string $name = 'MOCKSESSID', \MonorepoBuilder20210913\Symfony\Component\HttpFoundation\Session\Storage\MetadataBag $metaBag = null)
    {
        $this->savePath = $savePath;
        $this->name = $name;
        $this->metaBag = $metaBag;
    }
    /**
     * @param \Symfony\Component\HttpFoundation\Request|null $request
     */
    public function createStorage($request) : \MonorepoBuilder20210913\Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface
    {
        return new \MonorepoBuilder20210913\Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage($this->savePath, $this->name, $this->metaBag);
    }
}
