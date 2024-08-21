<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Merge\Arrays;

use Symplify\MonorepoBuilder\Merge\JsonSchema;

final class ArraySorter
{
    /**
     * @param mixed[] $array
     * @return mixed[]
     */
    public function recursiveSort(array $array): array
    {
        if ($array === []) {
            return $array;
        }

        if ($this->isSequential($array)) {
            sort($array);
        } else {
            ksort($array);
        }

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = $this->recursiveSort($value);
            }
        }

        return $array;
    }

    /**
     * @param mixed[] $orderedProperty
     * @param mixed[] $array
     * @return mixed[]
     */
    public function recursiveSortBySchema(array $orderedProperty,array $array): array
    {

        if ($array === []) {
            return $array;
        }

        if ($this->isSequential($array)) {
            sort($array);
        } else {
            uksort($array,static function (string $key1, string $key2) use ($orderedProperty) : int {
                if (!in_array($key1,$orderedProperty,true)){
                    return PHP_INT_MAX;
                }

                return array_search($key1,$orderedProperty,true) <=> array_search($key2,$orderedProperty,true);
            });
        }

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = $this->recursiveSort($value);
            }
        }

        return $array;
    }

    /**
     * @param mixed[] $array
     */
    private function isSequential(array $array): bool
    {
        $zeroToItemCount = range(0, count($array) - 1);
        return array_keys($array) === $zeroToItemCount;
    }
}
