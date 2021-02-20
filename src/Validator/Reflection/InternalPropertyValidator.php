<?php

declare(strict_types=1);

namespace ObjectMapper\Validator\Reflection;

use ObjectMapper\Validator\Context;
use ObjectMapper\Validator\Exception\UnprocessableData;

/** @internal */
final class InternalPropertyValidator implements PropertyValidator
{
    private TypeValidator $typeValidator;

    public function __construct(TypeValidator $typeValidator)
    {
        $this->typeValidator = $typeValidator;
    }

    public function validate(object $data, ?Context $context = null) : Context
    {
        if (!$data instanceof PropertyValidatorData) {
            throw UnprocessableData::expectedClass(PropertyValidatorData::class);
        }

        if ($context === null) {
            $context = Context::create();
        }

        $property = $data->property();

        if (!$property->hasType()) {
            return $context;
        }

        /** @psalm-suppress PossiblyNullArgument */
        return $this->typeValidator->validate(
            TypeValidatorData::create($data->value(), $property->getType()),
            $context
        );
    }
}