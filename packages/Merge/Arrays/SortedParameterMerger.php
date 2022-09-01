<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge\Arrays;

use MonorepoBuilder202209\Symplify\PackageBuilder\Yaml\ParametersMerger;
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
    public function mergeRecursiveAndSort(array $firstArray, array $secondArray) : array
    {
        $mergedArray = $this->parametersMerger->mergeWithCombine($firstArray, $secondArray);
        return $this->arraySorter->recursiveSort($mergedArray);
    }
    /**
     * @param mixed[] $firstArray
     * @param mixed[] $secondArray
     * @return mixed[]
     */
    public function mergeAndSort(array $firstArray, array $secondArray) : array
    {
        $mergedArray = $this->parametersMerger->merge($firstArray, $secondArray);
        return $this->arraySorter->recursiveSort($mergedArray);
    }
}
