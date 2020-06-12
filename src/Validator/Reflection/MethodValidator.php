<?php

declare(strict_types=1);

namespace ObjectMapper\Validator\Reflection;

use ObjectMapper\Validator\Reflection\Method\ParameterValidator;
use ReflectionMethod;
use function array_values;
use function count;

class MethodValidator
{
    private ParameterValidator $parameterValidator;

    public function __construct(ParameterValidator $parameterValidator)
    {
        $this->parameterValidator = $parameterValidator;
    }

    /** @param array<mixed> $arguments */
    public function isValid(array $arguments, ReflectionMethod $method) : bool
    {
        if (!$this->hasValidArgumentCount(count($arguments), $method)) {
            return false;
        }

        $parameters = $method->getParameters();

        foreach (array_values($arguments) as $index => $argument) {
            $parameter = $parameters[$index];

            if (!$this->parameterValidator->isValid($argument, $parameter)) {
                return false;
            }
        }

        return true;
    }

    private function hasValidArgumentCount(int $argumentCount, ReflectionMethod $method) : bool
    {
        return !(
            $argumentCount < $method->getNumberOfRequiredParameters()
            || $argumentCount > $method->getNumberOfParameters()
        );
    }
}
