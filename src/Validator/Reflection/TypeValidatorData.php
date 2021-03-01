<?php

declare(strict_types=1);

namespace ObjectMapper\Validator\Reflection;

use ReflectionType;

final class TypeValidatorData
{
    /** @var mixed */
    private $value;
    private ReflectionType $type;

    /** @param mixed $value */
    private function __construct($value, ReflectionType $type)
    {
        $this->value = $value;
        $this->type  = $type;
    }

    /** @param mixed $value */
    public static function create($value, ReflectionType $reflectionType): self
    {
        return new self($value, $reflectionType);
    }

    /** @return mixed */
    public function value()
    {
        return $this->value;
    }

    public function type(): ReflectionType
    {
        return $this->type;
    }
}
