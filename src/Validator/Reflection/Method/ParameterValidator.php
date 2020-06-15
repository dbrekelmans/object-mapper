<?php

declare(strict_types=1);

namespace ObjectMapper\Validator\Reflection\Method;

use ObjectMapper\Validator\Context;
use ObjectMapper\Validator\Exception\UnprocessableData;
use ObjectMapper\Validator\Reflection\TypeValidator;
use ObjectMapper\Validator\Validator;
use ReflectionParameter;

class ParameterValidator implements Validator
{
    private TypeValidator $typeValidator;

    public function __construct(TypeValidator $typeValidator)
    {
        $this->typeValidator = $typeValidator;
    }

    /** @param mixed $value */
    public static function data($value, ReflectionParameter $parameter) : ParameterValidatorData
    {
        return ParameterValidatorData::create($value, $parameter);
    }

    public function validate(object $data, ?Context $context = null) : Context
    {
        if (!$data instanceof ParameterValidatorData) {
            throw new UnprocessableData('Use ParameterValidator::data() to create a processable data object.');
        }

        if ($context === null) {
            $context = Context::create();
        }

        $parameter = $data->parameter();

        if (!$parameter->hasType()) {
            return $context;
        }

        return $this->typeValidator->validate(TypeValidator::data($data->value(), $parameter->getType()), $context);
    }
}

final class ParameterValidatorData
{
    /** @var mixed */
    private $value;
    private ReflectionParameter $parameter;

    /** @param mixed $value */
    public function __construct($value, ReflectionParameter $parameter)
    {
        $this->value = $value;
        $this->parameter = $parameter;
    }

    /** @param mixed $value */
    public static function create($value, ReflectionParameter $parameter) : self
    {
        return new self($value, $parameter);
    }

    /** @return mixed */
    public function value()
    {
        return $this->value;
    }

    public function parameter() : ReflectionParameter
    {
        return $this->parameter;
    }
}
