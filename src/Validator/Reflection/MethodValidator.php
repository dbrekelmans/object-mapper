<?php

declare(strict_types=1);

namespace ObjectMapper\Validator\Reflection;

use ObjectMapper\Validator\Context;
use ObjectMapper\Validator\Exception\UnprocessableData;
use ObjectMapper\Validator\Reflection\Method\ParameterValidator;
use ObjectMapper\Validator\Validator;
use ObjectMapper\Validator\Violation;
use ReflectionMethod;
use function array_values;
use function count;
use function sprintf;

class MethodValidator implements Validator
{
    private ParameterValidator $parameterValidator;

    public function __construct(ParameterValidator $parameterValidator)
    {
        $this->parameterValidator = $parameterValidator;
    }

    /** @param array<mixed> $arguments */
    public static function data(array $arguments, ReflectionMethod $method) : MethodValidatorData
    {
        return MethodValidatorData::create($arguments, $method);
    }

    public function validate(object $data, ?Context $context = null) : Context
    {
        if (!$data instanceof MethodValidatorData) {
            throw new UnprocessableData('Use MethodValidator::data() to create a processable data object.');
        }

        if ($context === null) {
            $context = Context::create();
        }

        $method = $data->method();
        $arguments = $data->arguments();

        $argumentCount = count($arguments);

        $numberOfRequiredParameters = $method->getNumberOfRequiredParameters();
        if ($argumentCount < $numberOfRequiredParameters) {
            $context->add(Violation::create(sprintf(
                '%d arguments provided, but %d parameters are required.',
                $argumentCount,
                $numberOfRequiredParameters
            )));
        }

        $numberOfParameters = $method->getNumberOfParameters();
        if ($argumentCount > $numberOfParameters) {
            $context->add(Violation::create(sprintf(
                '%d arguments provided, but %d parameters exist.',
                $argumentCount,
                $numberOfParameters
            )));
        }

        $parameters = $method->getParameters();

        foreach (array_values($arguments) as $index => $argument) {
            $parameter = $parameters[$index];

            $context = $this->parameterValidator->validate(ParameterValidator::data($argument, $parameter), $context);
        }

        return $context;
    }
}

class MethodValidatorData
{
    /**
     * @psalm-var list<mixed>
     * @var array<mixed>
     */
    private array $arguments;
    private ReflectionMethod $method;

    /** @param array<mixed> $arguments */
    private function __construct(array $arguments, ReflectionMethod $method)
    {
        $this->arguments = array_values($arguments);
        $this->method = $method;
    }

    /**
     * @psalm-param list<mixed> $arguments
     * @param array<mixed> $arguments
     */
    public static function create(array $arguments, ReflectionMethod $method) : self
    {
        return new self($arguments, $method);
    }

    /**
     * @psalm-return list<mixed>
     * @return array<mixed>
     */
    public function arguments() : array
    {
        return $this->arguments;
    }

    public function method() : ReflectionMethod
    {
        return $this->method;
    }
}
