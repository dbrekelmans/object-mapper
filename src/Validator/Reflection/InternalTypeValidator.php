<?php

declare(strict_types=1);

namespace ObjectMapper\Validator\Reflection;

use ObjectMapper\Validator\Context;
use ObjectMapper\Validator\Exception\UnprocessableData;
use ObjectMapper\Validator\Violation;
use ReflectionNamedType;
use SebastianBergmann\Type\Type;

use function sprintf;

/** @internal */
final class InternalTypeValidator implements TypeValidator
{
    public function validate(object $data, ?Context $context = null): Context
    {
        if (!$data instanceof TypeValidatorData) {
            throw UnprocessableData::expectedClass(TypeValidatorData::class);
        }

        if ($context === null) {
            $context = Context::create();
        }

        $reflectionType = $data->type();
        $value = $data->value();

        if ($reflectionType instanceof ReflectionNamedType) {
            $typeName = $reflectionType->getName();
        } else {
            $typeName = $reflectionType->__toString();
        }

        $valueType = Type::fromValue($value, $value === null);
        $type = Type::fromName($typeName, $reflectionType->allowsNull());

        if (!$type->isAssignable($valueType)) {
            $context->add(Violation::create(sprintf(
                'Argument of type "%s" provided, but "%s" expected.',
                $valueType->name(),
                $type->name()
            )));
        }

        return $context;
    }
}
