<?php

declare(strict_types=1);

namespace ObjectMapper;

use ObjectMapper\Exception\DuplicateEntry;
use ObjectMapper\Exception\NotFound;
use function md5;
use function sprintf;

final class MappingRegistry
{
    /** @psalm-var array<string, Mapping> */
    private array $registry = [];

    /** @throws DuplicateEntry */
    public function add(Mapping $mapping) : void
    {
        $from = $mapping->from();
        $to = $mapping->to();

        if ($this->has($from, $to)) {
            throw new DuplicateEntry(sprintf('Mapping from "%s" to "%s" is already registered.', $from, $to));
        }

        $this->registry[$this->generateIndex($from, $to)] = $mapping;
    }

    /**
     * @psalm-var class-string $from
     * @psalm-var class-string $to
     *
     * @throws NotFound
     */
    public function get(string $from, string $to) : Mapping
    {
        if (!$this->has($from, $to)) {
            throw new NotFound(sprintf('No mapping registered from "%s" to "%s"', $from, $to));
        }

        return $this->registry[$this->generateIndex($from, $to)];
    }

    /**
     * @psalm-var class-string $from
     * @psalm-var class-string $to
     */
    private function has(string $from, string $to) : bool
    {
        return isset($this->registry[$this->generateIndex($from, $to)]);
    }

    /**
     * @psalm-var class-string $from
     * @psalm-var class-string $to
     */
    private function generateIndex(string $from, string $to) : string
    {
        return md5($from . $to);
    }
}
