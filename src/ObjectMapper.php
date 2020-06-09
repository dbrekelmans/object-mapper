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

        $constructed = (new ConstructorMapper())->map($source, $target, $mapping->constructor());

        $constructed;

        // TODO: execute property and method mapping

        return $constructed;
    }
}
