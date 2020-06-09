<?php

declare(strict_types=1);

namespace ObjectMapper\Mapping;

final class Argument
{
    private Source $source;

    private function __construct(Source $source)
    {
        $this->source = $source;
    }

    public static function create(Source $source) : self
    {
        return new self($source);
    }

    public function source() : Source
    {
        return $this->source;
    }
}
