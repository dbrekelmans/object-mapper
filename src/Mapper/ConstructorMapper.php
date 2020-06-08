<?php

declare(strict_types=1);

namespace ObjectMapper\Mapper;

use ObjectMapper\Extractor\Exception\ExtractionError;
use ObjectMapper\Extractor\Extractor;
use ObjectMapper\Mapper\Exception\MappingError;
use ObjectMapper\Mapping\Constructor;

final class ConstructorMapper
{
    private Extractor $extractor;

    public function __construct(Extractor $extractor)
    {
        $this->extractor = $extractor;
    }

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
                $parameters[] = $this->extractor->extract($from, $mapping);
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
