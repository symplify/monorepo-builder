<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Merge\Arrays;

use Symplify\MonorepoBuilder\Merge\JsonSchema;
use Symplify\PackageBuilder\Yaml\ParametersMerger;

final class SortedParameterMerger
{
    public function __construct(
        private ParametersMerger $parametersMerger,
        private ArraySorter $arraySorter
    ) {
    }

    /**
     * @param mixed[] $firstArray
     * @param mixed[] $secondArray
     * @return mixed[]
     */
    public function mergeRecursiveAndSort(string $composerPropertyName,array $firstArray, array $secondArray): array
    {
        $mergedArray = $this->parametersMerger->mergeWithCombine($firstArray, $secondArray);
        return $this->recursiveSortBySchema($composerPropertyName,$mergedArray);
    }

    /**
     * @param mixed[]  $mergedArray
     * @return mixed[]
     */
    private function recursiveSortBySchema(string $composerPropertyName,array $mergedArray): array
    {
        $propertyDefinitions = JsonSchema::getPropertyDefinitions($composerPropertyName);

        if ($propertyDefinitions === []){
            return $this->arraySorter->recursiveSort($mergedArray);
        }

        return $this->arraySorter->recursiveSortBySchema($propertyDefinitions,$mergedArray);
    }

    /**
     * @param mixed[] $firstArray
     * @param mixed[] $secondArray
     * @return mixed[]
     */
    public function mergeAndSort(string $composerPropertyName, array $firstArray, array $secondArray): array
    {
        $mergedArray = $this->parametersMerger->merge($firstArray, $secondArray);

        return $this->recursiveSortBySchema($composerPropertyName,$mergedArray);
    }
}
