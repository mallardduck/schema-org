<?php

namespace Spatie\SchemaOrg\Generator\Parser\Tasks;

use Spatie\SchemaOrg\Generator\Type;

class ParseType extends Task
{
    public function __invoke(): ?Type
    {
        $type = new Type();

        $type->name = $this->getDefinitionProperty('rdfs:label');

        if (in_array($type->name, ['', 'DataType', 'Float', 'Integer', 'URL', 'Class'])) {
            return null;
        }

        $subClassOf = $this->getWrappedDefinitionProperty('rdfs:subClassOf');

        if ($subClassOf->count() > 0) {
            $type->parents = array_filter($subClassOf
                ->map(function ($parent, $key) {
                    return $this->getResourceName($parent);
                })->toArray());

            if (empty($type->parents)) {
                return null;
            }
        }

        $type->description = $this->getDefinitionProperty('rdfs:comment');

        $type->resource = $this->getResource();

        if (array_key_exists('https://schema.org/source', $this->definition)) {
            $type->source = $this->getWrappedDefinitionProperty('https://schema.org/source')
                ->map(function ($value) {
                    return $this->getResource($value);
                })->toArray();
        }

        if ('Intangible' === $this->getDefinitionProperty('rdfs:label')) {
            var_dump($type);die;
        }

        return $type;
    }
}
