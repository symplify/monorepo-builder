<?php

declare (strict_types=1);
namespace MonorepoBuilder20220503\Symplify\ComposerJsonManipulator\Json;

final class JsonCleaner
{
    /**
     * @param array<int|string, mixed> $data
     * @return array<int|string, mixed>
     */
    public function removeEmptyKeysFromJsonArray(array $data) : array
    {
        foreach ($data as $key => $value) {
            if (!\is_array($value)) {
                continue;
            }
            if ($value === []) {
                unset($data[$key]);
            } else {
                $data[$key] = $this->removeEmptyKeysFromJsonArray($value);
            }
        }
        return $data;
    }
}
