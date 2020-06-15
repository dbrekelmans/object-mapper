<?php

declare(strict_types=1);

namespace ObjectMapper\Validator\Reflection;

use ObjectMapper\Validator\Context;
use ObjectMapper\Validator\Exception\UnprocessableData;
use ObjectMapper\Validator\Validator;
use ObjectMapper\Validator\Violation;
use ReflectionNamedType;
use ReflectionType;
use SebastianBergmann\Type\Type;
use function sprintf;

class TypeValidator implements Validator
{
    /** @param mixed $value */
    public static function data($value, ReflectionType $reflectionType) : TypeValidatorData
    {
        return TypeValidatorData::create($value, $reflectionType);
    }

    public function validate(object $data, ?Context $context = null) : Context
    {
        if (!$data instanceof TypeValidatorData) {
            throw new UnprocessableData('Use TypeValidator::data() to create a processable data object.');
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

final class TypeValidatorData
{
    /** @var mixed */
    private $value;
    private ReflectionType $type;

    /** @param mixed $value */
    public function __construct($value, ReflectionType $type)
    {
        $this->value = $value;
        $this->type = $type;
    }

    /** @param mixed $value */
    public static function create($value, ReflectionType $reflectionType) : self
    {
        return new self($value, $reflectionType);
    }

    /** @return mixed */
    public function value()
    {
        return $this->value;
    }

    public function type() : ReflectionType
    {
        return $this->type;
    }
}
