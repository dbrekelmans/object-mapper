<?php

declare(strict_types=1);

namespace ObjectMapper;

use ObjectMapper\Exception\NotFound;
use ObjectMapper\Mapper\Exception\MappingError;
use ObjectMapper\Mapper\MapperFactory;
use ObjectMapper\Mapping\Registry;
use function get_class;

final class ObjectMapper
{
    private Registry $registry;
    private MapperFactory $factory;

    public function __construct(Registry $registry, MapperFactory $factory)
    {
        $this->registry = $registry;
        $this->factory = $factory;
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

        $constructed = $this->factory->constructor()->map($from, $to, $mapping->constructor());

        $constructed;

        // TODO: execute property and method mapping

        return $constructed;
    }
}
