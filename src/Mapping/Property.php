<?php

declare(strict_types=1);

namespace ObjectMapper\Mapping;

final class Property
{
    private string $name;
    private Source $source;

    private function __construct(string $name, Source $source/*, array $constraints, array $transformers*/)
    {
        $this->name = $name;
        $this->source = $source;
    }

    public static function create(string $name, Source $source/*, array $constraints, array $transformers*/): self
    {
        return new self($name, $source/*, $constraints, $transformers*/);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function source(): Source
    {
        return $this->source;
    }
}
