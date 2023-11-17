<?php

declare (strict_types=1);
namespace MonorepoBuilderPrefix202311\Symplify\SymplifyKernel\ValueObject;

use MonorepoBuilderPrefix202311\Symfony\Component\Console\Application;
use MonorepoBuilderPrefix202311\Symfony\Component\Console\Command\Command;
use MonorepoBuilderPrefix202311\Symfony\Component\HttpKernel\KernelInterface;
use MonorepoBuilderPrefix202311\Symplify\PackageBuilder\Console\Input\StaticInputDetector;
use MonorepoBuilderPrefix202311\Symplify\PackageBuilder\Console\Style\SymfonyStyleFactory;
use MonorepoBuilderPrefix202311\Symplify\SymplifyKernel\Contract\LightKernelInterface;
use MonorepoBuilderPrefix202311\Symplify\SymplifyKernel\Exception\BootException;
use Throwable;
/**
 * @api
 */
final class KernelBootAndApplicationRun
{
    /**
     * @var class-string<(KernelInterface | LightKernelInterface)>
     */
    private $kernelClass;
    /**
     * @var string[]
     */
    private $extraConfigs = [];
    /**
     * @param class-string<KernelInterface|LightKernelInterface> $kernelClass
     * @param string[] $extraConfigs
     */
    public function __construct(string $kernelClass, array $extraConfigs = [])
    {
        $this->kernelClass = $kernelClass;
        $this->extraConfigs = $extraConfigs;
        $this->validateKernelClass($this->kernelClass);
    }
    public function run() : void
    {
        try {
            $this->booKernelAndRunApplication();
        } catch (Throwable $throwable) {
            $symfonyStyleFactory = new SymfonyStyleFactory();
            $symfonyStyle = $symfonyStyleFactory->create();
            $symfonyStyle->error($throwable->getMessage());
            exit(Command::FAILURE);
        }
    }
    /**
     * @return \Symfony\Component\HttpKernel\KernelInterface|\Symplify\SymplifyKernel\Contract\LightKernelInterface
     */
    private function createKernel()
    {
        // random has is needed, so cache is invalidated and changes from config are loaded
        $kernelClass = $this->kernelClass;
        if (\is_a($kernelClass, LightKernelInterface::class, \true)) {
            return new $kernelClass();
        }
        $environment = 'prod' . \random_int(1, 100000);
        return new $kernelClass($environment, StaticInputDetector::isDebug());
    }
    private function booKernelAndRunApplication() : void
    {
        $kernel = $this->createKernel();
        if ($kernel instanceof LightKernelInterface) {
            $container = $kernel->createFromConfigs($this->extraConfigs);
        } else {
            $kernel->boot();
            $container = $kernel->getContainer();
        }
        /** @var Application $application */
        $application = $container->get(Application::class);
        // remove --no-interaction (with -n shortcut) option from Application
        // because we need to create option with -n shortcuts too
        // for example: --dry-run with shortcut -n
        $inputDefinition = $application->getDefinition();
        $options = $inputDefinition->getOptions();
        $options = \array_filter($options, static function ($option) : bool {
            return $option->getName() !== 'no-interaction';
        });
        $inputDefinition->setOptions($options);
        exit($application->run());
    }
    /**
     * @param class-string $kernelClass
     */
    private function validateKernelClass(string $kernelClass) : void
    {
        if (\is_a($kernelClass, KernelInterface::class, \true)) {
            return;
        }
        if (\is_a($kernelClass, LightKernelInterface::class, \true)) {
            return;
        }
        $currentValueType = \get_debug_type($kernelClass);
        $errorMessage = \sprintf('Class "%s" must by type of "%s" or "%s". "%s" given', $kernelClass, KernelInterface::class, LightKernelInterface::class, $currentValueType);
        throw new BootException($errorMessage);
    }
}
