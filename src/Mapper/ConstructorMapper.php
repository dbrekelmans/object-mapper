<?php

declare(strict_types=1);

namespace ObjectMapper\Mapper;

use ObjectMapper\Extractor\Exception\ExtractionError;
use ObjectMapper\Mapper\Exception\MappingError;
use ObjectMapper\Mapping\Constructor;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionNamedType;
use SebastianBergmann\Type\Type;
use function count;
use function sprintf;

/** @internal */
final class ConstructorMapper
{
    /**
     * @template T
     *
     * @psalm-param class-string<T> $to
     *
     * @psalm-return T
     *
     * @throws MappingError
     */
    public function map(object $from, string $to, Constructor $constructor) : object
    {
        try {
            $reflectionClass = new ReflectionClass($to);
        } catch (ReflectionException $e) {
            throw new MappingError(sprintf('Class "%s" does not exist.', $to));
        }

        $arguments = [];
        foreach ($constructor->parameters() as $parameter) {
            $mapping = $parameter->from();

            try {
                // TODO: refactor to Arguments class
                $arguments[] = $mapping->extractor()->extract($from, $mapping->target());
            } catch (ExtractionError $exception) {
                throw new MappingError(
                    'Unable to determine parameter value from provided object.',
                    0,
                    $exception
                );
            }
        }

        // TODO: refactor to ArgumentsValidator
        $constructorReflectionMethod = $reflectionClass->getConstructor();
        $this->validateNumberOfArguments(count($arguments), $constructorReflectionMethod);
        $this->validateArgumentTypes($arguments, $constructorReflectionMethod);

        return new $to(...$arguments);
    }

    /** @throws MappingError */
    private function validateNumberOfArguments(
        int $numberOfProvidedArguments,
        ?ReflectionMethod $constructorReflectionMethod
    ) : void {
        $numberOfRequiredParameters = 0;
        $numberOfParameters = 0;

        if ($constructorReflectionMethod !== null) {
            $numberOfRequiredParameters = $constructorReflectionMethod->getNumberOfRequiredParameters();
            $numberOfParameters = $constructorReflectionMethod->getNumberOfParameters();
        }

        if ($numberOfProvidedArguments < $numberOfRequiredParameters) {
            throw new MappingError(sprintf(
                '%d parameters required, but only %d arguments provided.',
                $numberOfRequiredParameters,
                $numberOfProvidedArguments
            ));
        }

        if ($numberOfProvidedArguments > $numberOfParameters) {
            throw new MappingError(sprintf(
                '%d arguments provided, but only %d parameters are defined.',
                $numberOfProvidedArguments,
                $numberOfParameters
            ));
        }
    }

    /**
     * @psalm-param list<mixed> $parameters
     * @param array<mixed> $arguments
     *
     * @throws MappingError
     */
    private function validateArgumentTypes(
        array $arguments,
        ?ReflectionMethod $constructorReflectionMethod
    ) : void {
        if ($constructorReflectionMethod === null) {
            return;
        }

        $reflectionParameters = $constructorReflectionMethod->getParameters();

        foreach ($arguments as $index => $argument) {
            $reflectionParameter = $reflectionParameters[$index];

            if (!$reflectionParameter->hasType()) {
                continue;
            }

            $reflectionParameterType = $reflectionParameter->getType();
            if (!$reflectionParameterType instanceof ReflectionNamedType) {
                continue;
            }

            $argumentType = Type::fromValue($argument, $argument === null);
            $parameterType = Type::fromName(
                $reflectionParameterType->getName(),
                $reflectionParameterType->allowsNull()
            );

            if (!$parameterType->isAssignable($argumentType)) {
                throw new MappingError(sprintf(
                    'Argument of type "%s" cannot be passed as parameter #%d of type "%s".',
                    $argumentType->name(),
                    $index,
                    $parameterType->name()
                ));
            }
        }
    }
}
