<?php

declare(strict_types=1);

namespace ObjectMapper;

use ObjectMapper\Mapper\ConstructorMapper;
use ObjectMapper\Mapper\Exception\MappingError;
use ObjectMapper\Mapper\PropertyMapper;
use ObjectMapper\Mapping\Exception\NotFound;
use ObjectMapper\Mapping\Registry;

use function get_class;

final class ObjectMapper
{
    private Registry $registry;
    private ConstructorMapper $constructorMapper;
    private PropertyMapper $propertyMapper;

    public function __construct(
        Registry $registry,
        ConstructorMapper $constructorMapper,
        PropertyMapper $propertyMapper
    ) {
        $this->registry          = $registry;
        $this->constructorMapper = $constructorMapper;
        $this->propertyMapper    = $propertyMapper;
    }

    /**
     * @throws NotFound
     * @throws MappingError
     *
     * @template T
     * @psalm-var class-string<T> $targetClass
     * @psalm-return T
     * @psalm-suppress InvalidReturnType
     */
    public function map(object $source, string $targetClass): object
    {
        $mapping = $this->registry->get(get_class($source), $targetClass);

        /** @psalm-suppress ArgumentTypeCoercion */
        $target = $this->constructorMapper->map($source, $targetClass, $mapping->constructor());

        foreach ($mapping->properties() as $property) {
            $this->propertyMapper->map($source, $target, $property);
        }

        // TODO: Method mapping

        /** @psalm-suppress InvalidReturnStatement */
        return $target;
    }
}
