<?php

namespace Spatie\SchemaOrg\Generator\Parser\Tasks;

use Spatie\SchemaOrg\Generator\Constant;

class ParseConstant extends Task
{
    public function __invoke(): ?Constant
    {
        $constant = new Constant();

        $constant->name = preg_replace('/\s+/', '_', $this->getDefinitionProperty('rdfs:label'));

        if (
            empty($constant->name) ||
            $this->isPartOfPending()
        ) {
            return null;
        }

        $constant->description = $this->getDefinitionProperty('rdfs:comment');

        $constant->value = $this->getResource();

        if (array_key_exists('http://schema.org/source', $this->definition)) {
            $constant->source = $this->getWrappedDefinitionProperty('http://schema.org/source')
                ->map(function ($value) {
                    return $this->getResource($value);
                })->toArray();
        }

        return $constant;
    }
}
