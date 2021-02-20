<?php

declare(strict_types=1);

namespace ObjectMapper\Validator\Reflection;

use ObjectMapper\Validator\Context;
use ObjectMapper\Validator\Exception\UnprocessableData;
use ObjectMapper\Validator\Violation;

use function array_values;
use function count;
use function sprintf;

/** @internal */
final class InternalMethodValidator implements MethodValidator
{
    private ParameterValidator $parameterValidator;

    public function __construct(ParameterValidator $parameterValidator)
    {
        $this->parameterValidator = $parameterValidator;
    }

    public function validate(object $data, ?Context $context = null): Context
    {
        if (!$data instanceof MethodValidatorData) {
            throw UnprocessableData::expectedClass(MethodValidatorData::class);
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

            $context = $this->parameterValidator->validate(
                ParameterValidatorData::create($argument, $parameter),
                $context
            );
        }

        return $context;
    }
}
