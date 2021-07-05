<?php

declare (strict_types=1);
namespace MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\Configuration;

use MonorepoBuilder20210705\PharIo\Version\Version;
use MonorepoBuilder20210705\Symfony\Component\Console\Input\InputInterface;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\Version\VersionFactory;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\ValueObject\Option;
final class VersionResolver
{
    /**
     * @var \Symplify\MonorepoBuilder\Release\Version\VersionFactory
     */
    private $versionFactory;
    public function __construct(\MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\Version\VersionFactory $versionFactory)
    {
        $this->versionFactory = $versionFactory;
    }
    public function resolveVersion(\MonorepoBuilder20210705\Symfony\Component\Console\Input\InputInterface $input, string $stage) : \MonorepoBuilder20210705\PharIo\Version\Version
    {
        /** @var string $versionArgument */
        $versionArgument = $input->getArgument(\MonorepoBuilder20210705\Symplify\MonorepoBuilder\ValueObject\Option::VERSION);
        return $this->versionFactory->createValidVersion($versionArgument, $stage);
    }
}
