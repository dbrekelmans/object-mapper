<?php

declare(strict_types=1);

namespace ObjectMapper\Validator\Reflection;

use ReflectionProperty;

final class PropertyValidatorData
{
    /** @var mixed */
    private $value;
    private ReflectionProperty $property;

    /** @param mixed $value */
    private function __construct($value, ReflectionProperty $property)
    {
        $this->value    = $value;
        $this->property = $property;
    }

    /** @param mixed $value */
    public static function create($value, ReflectionProperty $reflectionProperty): self
    {
        return new self($value, $reflectionProperty);
    }

    /** @return mixed */
    public function value()
    {
        return $this->value;
    }

    public function property(): ReflectionProperty
    {
        return $this->property;
    }
}
