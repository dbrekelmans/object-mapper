<?php

declare(strict_types=1);

namespace ObjectMapper\Mapper;

use ObjectMapper\Extractor\Exception\ExtractionError;
use ObjectMapper\Mapper\Exception\MappingError;
use ObjectMapper\Mapping\Constructor;
use ObjectMapper\Validator\Exception\UnprocessableData;
use ObjectMapper\Validator\Reflection\MethodValidator;
use ReflectionClass;
use ReflectionException;
use function sprintf;

/** @internal */
final class ConstructorMapper
{
    private MethodValidator $methodValidator;

    public function __construct(MethodValidator $methodValidator)
    {
        $this->methodValidator = $methodValidator;
    }

    /**
     * @template T
     *
     * @psalm-param class-string<T> $target
     *
     * @psalm-return T
     *
     * @throws MappingError
     */
    public function map(object $source, string $target, Constructor $constructor) : object
    {
        try {
            $reflectionClass = new ReflectionClass($target);
        } catch (ReflectionException $exception) {
            throw new MappingError(sprintf('Class "%s" does not exist.', $target));
        }

        $arguments = [];
        foreach ($constructor->arguments() as $argument) {
            $mapping = $argument->source();

            try {
                $arguments[] = $mapping->extractor()->extract($source, $mapping->data());
            } catch (ExtractionError $exception) {
                throw new MappingError(
                    'Unable to extract argument value from source.',
                    0,
                    $exception
                );
            }
        }

        $constructorReflectionMethod = $reflectionClass->getConstructor();
        if ($constructorReflectionMethod !== null) {
            try {
                $context = $this->methodValidator->validate(
                    MethodValidator::data($arguments, $constructorReflectionMethod)
                );
            } catch (UnprocessableData $exception) {
                throw new MappingError('Unable to validate constructor method arguments.', 0, $exception);
            }

            $violations = $context->violations();

            if (!empty($violations)) {
                throw MappingError::violations($violations);
            }
        }

        return new $target(...$arguments);
    }
}
