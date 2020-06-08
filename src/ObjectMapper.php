<?php

declare(strict_types=1);

namespace ObjectMapper;

use ObjectMapper\Mapper\ConstructorMapper;
use ObjectMapper\Mapper\Exception\MappingError;
use ObjectMapper\Mapping\Exception\NotFound;
use ObjectMapper\Mapping\Registry;
use function get_class;

final class ObjectMapper
{
    private Registry $registry;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @template T
     *
     * @psalm-var class-string<T> $to
     *
     * @psalm-return T
     *
     * @throws NotFound
     * @throws MappingError
     */
    public function map(object $from, string $to) : object
    {
        $mapping = $this->registry->get(get_class($from), $to);

        $constructed = (new ConstructorMapper())->map($from, $to, $mapping->constructor());

        $constructed;

        // TODO: execute property and method mapping

        return $constructed;
    }
}
