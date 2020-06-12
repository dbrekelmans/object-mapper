<?php

declare(strict_types=1);

namespace ObjectMapper\Mapper;

use ObjectMapper\Extractor\Exception\ExtractionError;
use ObjectMapper\Mapper\Exception\MappingError;
use ObjectMapper\Mapping\Constructor;
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
        } catch (ReflectionException $e) {
            throw new MappingError(sprintf('Class "%s" does not exist.', $target));
        }

        $arguments = [];
        foreach ($constructor->arguments() as $argument) {
            $mapping = $argument->source();

            try {
                // TODO: refactor to Arguments class
                $arguments[] = $mapping->extractor()->extract($source, $mapping->data());
            } catch (ExtractionError $exception) {
                throw new MappingError(
                    'Unable to determine argument value from provided object.',
                    0,
                    $exception
                );
            }
        }

        $constructorReflectionMethod = $reflectionClass->getConstructor();
        if (
            $constructorReflectionMethod !== null
            && !$this->methodValidator->isValid($arguments, $constructorReflectionMethod)
        ) {
            throw new MappingError('TODO: get error message from validators.');
        }

        return new $target(...$arguments);
    }
}
