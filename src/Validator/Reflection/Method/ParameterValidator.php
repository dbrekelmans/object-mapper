<?php

declare(strict_types=1);

namespace ObjectMapper\Validator\Reflection\Method;

use ObjectMapper\Validator\Reflection\TypeValidator;
use ReflectionParameter;

class ParameterValidator
{
    private TypeValidator $typeValidator;

    public function __construct(TypeValidator $typeValidator)
    {
        $this->typeValidator = $typeValidator;
    }

    /** @param mixed $value */
    public function isValid($value, ReflectionParameter $parameter) : bool
    {
        if (!$parameter->hasType()) {
            return true;
        }

        return $this->typeValidator->isValid($value, $parameter->getType());
    }
}
