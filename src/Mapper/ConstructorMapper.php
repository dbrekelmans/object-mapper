<?php

declare(strict_types=1);

namespace ObjectMapper\Mapper;

use ObjectMapper\Extractor\Exception\ExtractionError;
use ObjectMapper\Mapper\Exception\MappingError;
use ObjectMapper\Mapping\Argument;
use ObjectMapper\Mapping\Constructor;
use ObjectMapper\Validator\Exception\UnprocessableData;
use ObjectMapper\Validator\Reflection\MethodValidator;
use ObjectMapper\Validator\Reflection\MethodValidatorData;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

use function sprintf;

final class ConstructorMapper
{
    private MethodValidator $methodValidator;

    public function __construct(MethodValidator $methodValidator)
    {
        $this->methodValidator = $methodValidator;
    }

    /**
     * @template T of object
     *
     * @psalm-param class-string<T> $target
     *
     * @psalm-return T
     *
     * @throws MappingError
     */
    public function map(object $source, string $target, Constructor $constructor): object
    {
        try {
            $reflectionClass = new ReflectionClass($target);
        } catch (ReflectionException $exception) {
            throw new MappingError(sprintf('Class "%s" does not exist.', $target), 0, $exception);
        }

        $arguments = $this->extractArgumentsFromSource($source, $constructor->arguments());

        $constructorReflectionMethod = $reflectionClass->getConstructor();
        if ($constructorReflectionMethod !== null) {
            $this->validateTargetConstructor($arguments, $constructorReflectionMethod);
        }

        /** @psalm-suppress MixedMethodCall */
        return new $target(...$arguments);
    }

    /**
     * @param array<Argument>|Argument[] $mappingArguments
     *
     * @return list<mixed>
     *
     * @throws MappingError
     */
    private function extractArgumentsFromSource(object $source, array $mappingArguments): array
    {
        $arguments = [];
        foreach ($mappingArguments as $mappingArgument) {
            $mapping = $mappingArgument->source();

            try {
                /** @psalm-suppress MixedAssignment */
                $arguments[] = $mapping->extractor()->extract($source, $mapping->data());
            } catch (ExtractionError $exception) {
                throw new MappingError(
                    'Unable to extract argument value from source.',
                    0,
                    $exception
                );
            }
        }

        return $arguments;
    }

    /**
     * @param list<mixed> $arguments
     *
     * @throws MappingError
     */
    private function validateTargetConstructor(array $arguments, ReflectionMethod $constructorReflectionMethod): void
    {
        try {
            $context = $this->methodValidator->validate(
                MethodValidatorData::create($arguments, $constructorReflectionMethod)
            );
        } catch (UnprocessableData $exception) {
            throw new MappingError('Unable to validate constructor method arguments.', 0, $exception);
        }

        $violations = $context->violations();

        if (!empty($violations)) {
            throw MappingError::violations($violations);
        }
    }
}
