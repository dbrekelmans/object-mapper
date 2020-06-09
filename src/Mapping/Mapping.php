<?php

declare(strict_types=1);

namespace ObjectMapper\Mapping;

/**
 * @template F of object
 * @template T of object
 */
interface Mapping
{
    /** @psalm-return class-string<F> */
    public function source() : string;

    /** @psalm-return class-string<T> */
    public function target() : string;

    public function constructor() : Constructor;
}
