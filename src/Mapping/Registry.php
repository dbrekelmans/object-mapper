<?php

declare(strict_types=1);

namespace ObjectMapper\Mapping;

use ObjectMapper\Mapping\Exception\DuplicateEntry;
use ObjectMapper\Mapping\Exception\NotFound;

use function md5;
use function sprintf;

final class Registry
{
    /** @psalm-var array<string, Mapping> */
    private array $registry = [];

    /** @throws DuplicateEntry */
    public function add(Mapping $mapping): void
    {
        $source = $mapping->source();
        $target = $mapping->target();

        if ($this->has($source, $target)) {
            throw new DuplicateEntry(sprintf(
                'Mapping source "%s" to target "%s" is already registered.',
                $source,
                $target
            ));
        }

        $this->registry[$this->generateIndex($source, $target)] = $mapping;
    }

    /**
     * @psalm-var class-string $source
     * @psalm-var class-string $target
     *
     * @throws NotFound
     */
    public function get(string $source, string $target): Mapping
    {
        if (!$this->has($source, $target)) {
            throw new NotFound(sprintf('No mapping registered from source "%s" to target "%s"', $source, $target));
        }

        return $this->registry[$this->generateIndex($source, $target)];
    }

    /**
     * @psalm-var class-string $source
     * @psalm-var class-string $target
     */
    private function has(string $source, string $target): bool
    {
        return isset($this->registry[$this->generateIndex($source, $target)]);
    }

    /**
     * @psalm-var class-string $source
     * @psalm-var class-string $target
     */
    private function generateIndex(string $source, string $target): string
    {
        return md5($source . $target);
    }
}
