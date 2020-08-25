<?php

namespace Spatie\SchemaOrg\Generator\Parser\Tasks;

use Tightenco\Collect\Support\Collection;

abstract class Task
{
    /** @array */
    protected $definition;

    public function __construct(array $definition)
    {
        $this->definition = $definition;
    }

    public static function fromJsonLdArray(array $jsonLdArray): self
    {
        return new static($jsonLdArray);
    }

    protected function getDefinitionProperty(string $selector): string
    {

        $property = $this->definition[$selector] ?? '';
        return is_array($property) && array_key_exists('@language', $property) && array_key_exists('@value', $property) ? $property['@value'] : $property;
    }

    protected function getWrappedDefinitionProperty(string $selector): Collection
    {
        $property = $this->definition[$selector] ?? [];
        if (count($property) === 1) {
            $property = [$property];
        }

        return collect($property);
    }

    protected function getResource(?array $schemaResource = null)
    {
        return $schemaResource['@id'] ?? $this->definition['@id'];
    }

    protected function getResourceName(?array $schemaResource = null)
    {
        return substr($this->getResource($schemaResource), 18);
    }

    protected function isPartOfPending(): bool
    {
        $isPartOf = $this->getWrappedDefinitionProperty('http://schema.org/isPartOf');
        if ($isPartOf->isEmpty()) {
            return false;
        }

        $filtered = $isPartOf->filter(static function ($schema) {
            return array_key_exists('@id', $schema) && $schema['@id'] === 'http://pending.schema.org';
        });
        if ($filtered->isEmpty()) {
            return false;
        }

        return true;
    }
}
