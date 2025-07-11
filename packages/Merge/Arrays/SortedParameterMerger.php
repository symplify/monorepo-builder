<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge\Arrays;

use Symplify\MonorepoBuilder\Merge\JsonSchema;
use MonorepoBuilderPrefix202507\Symplify\PackageBuilder\Yaml\ParametersMerger;
final class SortedParameterMerger
{
    /**
     * @var \Symplify\PackageBuilder\Yaml\ParametersMerger
     */
    private $parametersMerger;
    /**
     * @var \Symplify\MonorepoBuilder\Merge\Arrays\ArraySorter
     */
    private $arraySorter;
    public function __construct(ParametersMerger $parametersMerger, \Symplify\MonorepoBuilder\Merge\Arrays\ArraySorter $arraySorter)
    {
        $this->parametersMerger = $parametersMerger;
        $this->arraySorter = $arraySorter;
    }
    /**
     * @param mixed[] $firstArray
     * @param mixed[] $secondArray
     * @return mixed[]
     */
    public function mergeRecursiveAndSort(string $composerPropertyName, array $firstArray, array $secondArray) : array
    {
        $mergedArray = $this->parametersMerger->mergeWithCombine($firstArray, $secondArray);
        return $this->recursiveSortBySchema($composerPropertyName, $mergedArray);
    }
    /**
     * @param mixed[]  $mergedArray
     * @return mixed[]
     */
    private function recursiveSortBySchema(string $composerPropertyName, array $mergedArray) : array
    {
        $propertyDefinitions = JsonSchema::getPropertyDefinitions($composerPropertyName);
        if ($propertyDefinitions === []) {
            return $this->arraySorter->recursiveSort($mergedArray);
        }
        return $this->arraySorter->recursiveSortBySchema($propertyDefinitions, $mergedArray);
    }
    /**
     * @param mixed[] $firstArray
     * @param mixed[] $secondArray
     * @return mixed[]
     */
    public function mergeAndSort(string $composerPropertyName, array $firstArray, array $secondArray) : array
    {
        $mergedArray = $this->parametersMerger->merge($firstArray, $secondArray);
        return $this->recursiveSortBySchema($composerPropertyName, $mergedArray);
    }
}
