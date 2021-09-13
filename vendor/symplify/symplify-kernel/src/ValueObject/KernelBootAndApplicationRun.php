<?php

declare (strict_types=1);
namespace MonorepoBuilder20210913\Symplify\SymplifyKernel\ValueObject;

use MonorepoBuilder20210913\Symfony\Component\Console\Application;
use MonorepoBuilder20210913\Symfony\Component\Console\Command\Command;
use MonorepoBuilder20210913\Symfony\Component\HttpKernel\KernelInterface;
use MonorepoBuilder20210913\Symplify\PackageBuilder\Console\Input\StaticInputDetector;
use MonorepoBuilder20210913\Symplify\PackageBuilder\Console\Style\SymfonyStyleFactory;
use MonorepoBuilder20210913\Symplify\PackageBuilder\Contract\HttpKernel\ExtraConfigAwareKernelInterface;
use MonorepoBuilder20210913\Symplify\SmartFileSystem\SmartFileInfo;
use MonorepoBuilder20210913\Symplify\SymplifyKernel\Exception\BootException;
use Throwable;
final class KernelBootAndApplicationRun
{
    /**
     * @var class-string
     */
    private $kernelClass;
    /**
     * @var string[]|SmartFileInfo[]
     */
    private $extraConfigs = [];
    /**
     * @param class-string $kernelClass
     * @param string[]|SmartFileInfo[] $extraConfigs
     */
    public function __construct(string $kernelClass, array $extraConfigs = [])
    {
        $this->setKernelClass($kernelClass);
        $this->extraConfigs = $extraConfigs;
    }
    public function run() : void
    {
        try {
            $this->booKernelAndRunApplication();
        } catch (\Throwable $throwable) {
            $symfonyStyleFactory = new \MonorepoBuilder20210913\Symplify\PackageBuilder\Console\Style\SymfonyStyleFactory();
            $symfonyStyle = $symfonyStyleFactory->create();
            $symfonyStyle->error($throwable->getMessage());
            exit(\MonorepoBuilder20210913\Symfony\Component\Console\Command\Command::FAILURE);
        }
    }
    private function createKernel() : \MonorepoBuilder20210913\Symfony\Component\HttpKernel\KernelInterface
    {
        // random has is needed, so cache is invalidated and changes from config are loaded
        $environment = 'prod' . \random_int(1, 100000);
        $kernelClass = $this->kernelClass;
        $kernel = new $kernelClass($environment, \MonorepoBuilder20210913\Symplify\PackageBuilder\Console\Input\StaticInputDetector::isDebug());
        $this->setExtraConfigs($kernel, $kernelClass);
        return $kernel;
    }
    private function booKernelAndRunApplication() : void
    {
        $kernel = $this->createKernel();
        if ($kernel instanceof \MonorepoBuilder20210913\Symplify\PackageBuilder\Contract\HttpKernel\ExtraConfigAwareKernelInterface && $this->extraConfigs !== []) {
            $kernel->setConfigs($this->extraConfigs);
        }
        $kernel->boot();
        $container = $kernel->getContainer();
        /** @var Application $application */
        $application = $container->get(\MonorepoBuilder20210913\Symfony\Component\Console\Application::class);
        exit($application->run());
    }
    private function setExtraConfigs(\MonorepoBuilder20210913\Symfony\Component\HttpKernel\KernelInterface $kernel, string $kernelClass) : void
    {
        if ($this->extraConfigs === []) {
            return;
        }
        if (\is_a($kernel, \MonorepoBuilder20210913\Symplify\PackageBuilder\Contract\HttpKernel\ExtraConfigAwareKernelInterface::class, \true)) {
            /** @var ExtraConfigAwareKernelInterface $kernel */
            $kernel->setConfigs($this->extraConfigs);
        } else {
            $message = \sprintf('Extra configs are set, but the "%s" kernel class is missing "%s" interface', $kernelClass, \MonorepoBuilder20210913\Symplify\PackageBuilder\Contract\HttpKernel\ExtraConfigAwareKernelInterface::class);
            throw new \MonorepoBuilder20210913\Symplify\SymplifyKernel\Exception\BootException($message);
        }
    }
    /**
     * @param class-string $kernelClass
     */
    private function setKernelClass(string $kernelClass) : void
    {
        if (!\is_a($kernelClass, \MonorepoBuilder20210913\Symfony\Component\HttpKernel\KernelInterface::class, \true)) {
            $message = \sprintf('Class "%s" must by type of "%s"', $kernelClass, \MonorepoBuilder20210913\Symfony\Component\HttpKernel\KernelInterface::class);
            throw new \MonorepoBuilder20210913\Symplify\SymplifyKernel\Exception\BootException($message);
        }
        $this->kernelClass = $kernelClass;
    }
}
