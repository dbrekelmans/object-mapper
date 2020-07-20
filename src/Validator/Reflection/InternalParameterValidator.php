<?php

declare(strict_types=1);

namespace ObjectMapper\Validator\Reflection;

use ObjectMapper\Validator\Context;
use ObjectMapper\Validator\Exception\UnprocessableData;

/** @internal */
final class InternalParameterValidator implements ParameterValidator
{
    private TypeValidator $typeValidator;

    public function __construct(TypeValidator $typeValidator)
    {
        $this->typeValidator = $typeValidator;
    }

    public function validate(object $data, ?Context $context = null) : Context
    {
        if (!$data instanceof ParameterValidatorData) {
            throw UnprocessableData::expectedClass(ParameterValidatorData::class);
        }

        if ($context === null) {
            $context = Context::create();
        }

        $parameter = $data->parameter();

        if (!$parameter->hasType()) {
            return $context;
        }

        return $this->typeValidator->validate(
            TypeValidatorData::create($data->value(), $parameter->getType()),
            $context
        );
    }
}
