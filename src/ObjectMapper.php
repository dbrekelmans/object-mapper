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
    private ConstructorMapper $constructorMapper;

    public function __construct(Registry $registry, ConstructorMapper $constructorMapper)
    {
        $this->registry = $registry;
        $this->constructorMapper = $constructorMapper;
    }

    /**
     * @template T
     *
     * @psalm-var class-string<T> $target
     *
     * @psalm-return T
     *
     * @throws NotFound
     * @throws MappingError
     */
    public function map(object $source, string $target) : object
    {
        $mapping = $this->registry->get(get_class($source), $target);

        $constructed = $this->constructorMapper->map($source, $target, $mapping->constructor());

        // TODO: execute property and method mapping

        return $constructed;
    }
}
