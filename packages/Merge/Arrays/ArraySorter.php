<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Merge\Arrays;

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
            uksort($array, static function (string $key1, string $key2) use ($orderedProperty): int {
                $pos1 = array_search($key1, $orderedProperty, true);
                $pos2 = array_search($key2, $orderedProperty, true);

                if ($pos1 === false && $pos2 === false) {
                    return 0;
                }

                if ($pos1 === false) {
                    return 1;
                }

                if ($pos2 === false) {
                    return -1;
                }

                return $pos1 <=> $pos2;
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
