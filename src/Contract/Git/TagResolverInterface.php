<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Contract\Git;

interface TagResolverInterface
{
    /**
     * @param string $gitDirectory
     */
    public function resolve($gitDirectory) : ?string;
}
