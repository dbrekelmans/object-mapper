<?php

declare(strict_types=1);

namespace ObjectMapper\Mapping;

/**
 * @template S of object
 * @template T of object
 */
interface Mapping
{
    /** @psalm-return class-string<S> */
    public function source(): string;

    /** @psalm-return class-string<T> */
    public function target(): string;

    public function constructor(): Constructor;

    /**
     * @return Property[]
     *
     * @psalm-return list<Property>
     */
    public function properties(): array;
}
