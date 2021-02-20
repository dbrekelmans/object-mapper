<?php

declare(strict_types=1);

namespace ObjectMapper\Mapper;

use ObjectMapper\Extractor\Exception\ExtractionError;
use ObjectMapper\Mapper\Exception\MappingError;
use ObjectMapper\Mapping\Property;

final class PropertyMapper
{
    /**
     * @throws MappingError
     */
    public function map(object $source, object $target, Property $property): void
    {
        $propertySource = $property->source();

        $propertyName = $property->name();
        try {
            /** @psalm-suppress MixedAssignment */
            $propertyValue = $propertySource->extractor()->extract($source, $propertySource->data());
        } catch (ExtractionError $exception) {
            throw new MappingError('Unable to extract property value from source.', 0, $exception);
        }

        // TODO: Use reflection to validate type is allowed for this property
        $target->$propertyName = $propertyValue;
    }
}
