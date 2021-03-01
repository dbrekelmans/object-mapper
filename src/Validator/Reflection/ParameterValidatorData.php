<?php

declare(strict_types=1);

namespace ObjectMapper\Validator\Reflection;

use ReflectionParameter;

final class ParameterValidatorData
{
    /** @var mixed */
    private $value;
    private ReflectionParameter $parameter;

    /** @param mixed $value */
    private function __construct($value, ReflectionParameter $parameter)
    {
        $this->value     = $value;
        $this->parameter = $parameter;
    }

    /** @param mixed $value */
    public static function create($value, ReflectionParameter $parameter): self
    {
        return new self($value, $parameter);
    }

    /** @return mixed */
    public function value()
    {
        return $this->value;
    }

    public function parameter(): ReflectionParameter
    {
        return $this->parameter;
    }
}
