<?php

declare(strict_types=1);

namespace ObjectMapper;

use ObjectMapper\Mapper\ConstructorMapper;
use ObjectMapper\Mapper\Exception\MappingError;
use ObjectMapper\Mapping\Exception\NotFound;
use ObjectMapper\Mapping\Registry;
use ObjectMapper\Validator\Reflection\InternalMethodValidator;
use ObjectMapper\Validator\Reflection\InternalParameterValidator;
use ObjectMapper\Validator\Reflection\InternalTypeValidator;
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

        $mapper = new ConstructorMapper(new InternalMethodValidator(new InternalParameterValidator(new InternalTypeValidator())));
        $constructed = $mapper->map($source, $target, $mapping->constructor());

        // TODO: execute property and method mapping

        return $constructed;
    }
}
