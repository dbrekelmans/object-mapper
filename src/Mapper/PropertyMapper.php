<?php

declare(strict_types=1);

namespace ObjectMapper\Mapper;

use ObjectMapper\Extractor\Exception\ExtractionError;
use ObjectMapper\Mapper\Exception\MappingError;
use ObjectMapper\Mapping\Property;
use ObjectMapper\Validator\Exception\UnprocessableData;
use ObjectMapper\Validator\Reflection\PropertyValidator;
use ObjectMapper\Validator\Reflection\PropertyValidatorData;
use ReflectionException;
use ReflectionProperty;

use function get_class;
use function sprintf;

final class PropertyMapper
{
    private PropertyValidator $propertyValidator;

    public function __construct(PropertyValidator $propertyValidator)
    {
        $this->propertyValidator = $propertyValidator;
    }

    /**
     * @template T of object
     *
     * @psalm-param T $target
     *
     * @psalm-return T
     *
     * @throws MappingError
     */
    public function map(object $source, object $target, Property $property): object
    {
        $propertySource = $property->source();

        $propertyName = $property->name();
        try {
            /** @psalm-suppress MixedAssignment */
            $propertyValue = $propertySource->extractor()->extract($source, $propertySource->data());
        } catch (ExtractionError $exception) {
            throw new MappingError('Unable to extract property value from source.', 0, $exception);
        }

        try {
            $reflectionProperty = new ReflectionProperty($target, $propertyName);
        } catch (ReflectionException $exception) {
            throw new MappingError(
                sprintf('Property "%s" does not exist on target class "%s"', $propertyName, get_class($target))
            );
        }

        $this->validateTargetProperty($propertyValue, $reflectionProperty);

        $target->$propertyName = $propertyValue;

        return $target;
    }

    /**
     * @param mixed $value
     *
     * @throws MappingError
     */
    private function validateTargetProperty($value, ReflectionProperty $reflectionProperty): void
    {
        try {
            $context = $this->propertyValidator->validate(
                PropertyValidatorData::create($value, $reflectionProperty)
            );
        } catch (UnprocessableData $exception) {
            throw new MappingError('Unable to validate property.', 0, $exception);
        }

        $violations = $context->violations();

        if (!empty($violations)) {
            throw MappingError::violations($violations);
        }
    }
}
