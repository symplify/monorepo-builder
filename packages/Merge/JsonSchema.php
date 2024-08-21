<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Merge;

use RuntimeException;
final class JsonSchema
{
    private array $composerSchema;

    private function __construct()
    {
        $json_schema = file_get_contents(__DIR__.'/../Resources/schema.json');

        if ($json_schema === false) {
            throw new RuntimeException('Json schema file cannot be read');
        }

        $this->composerSchema = json_decode($json_schema,true, 512, JSON_THROW_ON_ERROR);
    }

    public static function getPropertyDefinitions(string $propertyName): array
    {
        return array_keys((new self())->composerSchema['definitions'][$propertyName]['properties'] ?? []);
    }

    public static function getProperties(): array
    {
        return array_keys((new self())->composerSchema['properties'] ?? []);
    }

}