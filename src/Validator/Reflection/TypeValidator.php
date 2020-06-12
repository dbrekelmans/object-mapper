<?php

declare(strict_types=1);

namespace ObjectMapper\Validator\Reflection;

use ReflectionNamedType;
use ReflectionType;
use SebastianBergmann\Type\Type;

class TypeValidator
{
    /** @param mixed $value */
    public function isValid($value, ReflectionType $reflectionType) : bool
    {
        if ($reflectionType instanceof ReflectionNamedType) {
            $reflectionTypeName = $reflectionType->getName();
        } else {
            $reflectionTypeName = $reflectionType->__toString();
        }

        $valueType = Type::fromValue($value, $value === null);
        $type = Type::fromName(
            $reflectionTypeName,
            $reflectionType->allowsNull()
        );

        return $type->isAssignable($valueType);
    }
}
