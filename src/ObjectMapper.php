<?php

declare(strict_types=1);

namespace ObjectMapper;

use ObjectMapper\Exception\NotFound;
use function get_class;

final class ObjectMapper
{
    private MappingRegistry $registry;

    public function __construct(MappingRegistry $registry)
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
     */
    public function map(object $from, string $to) : object
    {
        $mapping = $this->registry->get(get_class($from), $to);

        // TODO: map
    }
}
