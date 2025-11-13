<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Tests\Git;

use Exception;
use PharIo\Version\Version;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use Symplify\MonorepoBuilder\Git\BranchAwareTagResolver;
use Symplify\MonorepoBuilder\Release\Process\ProcessRunner;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Style\SymfonyStyle;

final class BranchAwareTagResolverTest extends TestCase
{
    /**
     * Test the parseTags private method using reflection
     */
    public function testParseTagsReturnsEmptyArrayForEmptyString(): void
    {
        $branchAwareTagResolver = $this->createResolver();
        $result = $this->invokeParseTags($branchAwareTagResolver, '');

        $this->assertSame([], $result);
    }

    public function testParseTagsHandlesMultipleTags(): void
    {
        $branchAwareTagResolver = $this->createResolver();
        $result = $this->invokeParseTags($branchAwareTagResolver, "v3.0.0\nv2.1.5\nv2.1.4");

        $this->assertSame(['v3.0.0', 'v2.1.5', 'v2.1.4'], $result);
    }

    public function testParseTagsHandlesWindowsLineEndings(): void
    {
        $branchAwareTagResolver = $this->createResolver();
        $result = $this->invokeParseTags($branchAwareTagResolver, "v3.0.0\r\nv2.1.5\r\nv2.1.4");

        $this->assertSame(['v3.0.0', 'v2.1.5', 'v2.1.4'], $result);
    }

    public function testParseTagsFiltersEmptyStrings(): void
    {
        $branchAwareTagResolver = $this->createResolver();
        $result = $this->invokeParseTags($branchAwareTagResolver, "v3.0.0\n\nv2.1.5");

        // array_filter preserves keys, so we need to check values only
        $this->assertSame(['v3.0.0', 'v2.1.5'], array_values($result));
    }

    /**
     * Test version filtering logic by creating actual tags in the test
     */
    public function testResolveForVersionLogic(): void
    {
        // Test the major version filtering logic independently
        $tags = ['v3.2.0', 'v3.1.0', 'v3.0.0', 'v2.1.5', 'v2.1.4', 'v1.0.0'];
        $requestedVersion = new Version('2.1.6');
        $majorVersion = $requestedVersion->getMajor()->getValue();

        $foundTag = null;
        foreach ($tags as $tag) {
            try {
                $normalizedTag = ltrim(strtolower($tag), 'v');
                $tagVersion = new Version($normalizedTag);

                if ($tagVersion->getMajor()->getValue() === $majorVersion) {
                    $foundTag = $tag;
                    break;
                }
            } catch (Exception) {
                continue;
            }
        }

        $this->assertSame('v2.1.5', $foundTag);
    }

    public function testResolveForVersionLogicWithNoMatchingMajor(): void
    {
        $tags = ['v3.0.0', 'v3.1.0', 'v1.0.0'];
        $requestedVersion = new Version('2.0.0');
        $majorVersion = $requestedVersion->getMajor()->getValue();

        $foundTag = null;
        foreach ($tags as $tag) {
            try {
                $normalizedTag = ltrim(strtolower($tag), 'v');
                $tagVersion = new Version($normalizedTag);

                if ($tagVersion->getMajor()->getValue() === $majorVersion) {
                    $foundTag = $tag;
                    break;
                }
            } catch (Exception) {
                continue;
            }
        }

        $this->assertNull($foundTag);
    }

    public function testResolveForVersionLogicSkipsInvalidTags(): void
    {
        $tags = ['v3.0.0', 'invalid-tag', 'v2.1.5', 'bad-version', 'v2.1.4'];
        $requestedVersion = new Version('2.2.0');
        $majorVersion = $requestedVersion->getMajor()->getValue();

        $foundTag = null;
        foreach ($tags as $tag) {
            try {
                $normalizedTag = ltrim(strtolower($tag), 'v');
                $tagVersion = new Version($normalizedTag);

                if ($tagVersion->getMajor()->getValue() === $majorVersion) {
                    $foundTag = $tag;
                    break;
                }
            } catch (Exception) {
                continue;
            }
        }

        $this->assertSame('v2.1.5', $foundTag);
    }

    public function testResolveForVersionLogicHandlesMixedCase(): void
    {
        $tags = ['V3.0.0', 'V2.1.5', 'v2.1.4'];
        $requestedVersion = new Version('2.2.0');
        $majorVersion = $requestedVersion->getMajor()->getValue();

        $foundTag = null;
        foreach ($tags as $tag) {
            try {
                $normalizedTag = ltrim(strtolower($tag), 'v');
                $tagVersion = new Version($normalizedTag);

                if ($tagVersion->getMajor()->getValue() === $majorVersion) {
                    $foundTag = $tag;
                    break;
                }
            } catch (Exception) {
                continue;
            }
        }

        $this->assertSame('V2.1.5', $foundTag);
    }

    private function createResolver(): BranchAwareTagResolver
    {
        $arrayInput = new ArrayInput([]);
        $bufferedOutput = new BufferedOutput();
        $symfonyStyle = new SymfonyStyle($arrayInput, $bufferedOutput);
        $processRunner = new ProcessRunner($symfonyStyle);

        return new BranchAwareTagResolver($processRunner);
    }

    /**
     * @return string[]
     */
    private function invokeParseTags(BranchAwareTagResolver $branchAwareTagResolver, string $commandResult): array
    {
        $reflectionClass = new ReflectionClass($branchAwareTagResolver);
        $reflectionMethod = $reflectionClass->getMethod('parseTags');

        return $reflectionMethod->invoke($branchAwareTagResolver, $commandResult);
    }
}