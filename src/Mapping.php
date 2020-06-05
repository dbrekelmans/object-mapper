<?php

declare(strict_types=1);

namespace ObjectMapper;

use ObjectMapper\Mapping\Constructor;

/**
 * @template F of object
 * @template T of object
 */
interface Mapping
{
    /** @psalm-return class-string<F> */
    public function from() : string;

    /** @psalm-return class-string<T> */
    public function to() : string;

    public function constructor() : ?Constructor;
}
