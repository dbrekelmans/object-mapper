<?php

declare(strict_types=1);

namespace ObjectMapper;

final class ObjectMapper
{
    private array $mappings = [];

    public function register(Mapping $mapping) : void
    {
        $this->mappings[] = $mapping;
    }

    public function getMappings() : array
    {
        return $this->mappings;
    }
}
