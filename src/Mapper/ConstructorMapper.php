<?php

declare(strict_types=1);

namespace ObjectMapper\Mapper;

use ObjectMapper\Extractor\Exception\ExtractionError;
use ObjectMapper\Mapper\Exception\MappingError;
use ObjectMapper\Mapping\Constructor;

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
        $parameters = [];
        foreach ($constructor->parameters() as $parameter) {
            $mapping = $parameter->from();

            try {
                $parameters[] = $mapping->extractor()->extract($from, $mapping->target());
            } catch (ExtractionError $exception) {
                throw new MappingError(
                    'Unable to determine parameter value from provided object.',
                    0,
                    $exception
                );
            }
        }

        return new $to(...$parameters);
    }
}
