<?php

declare(strict_types=1);

namespace ObjectMapper\Mapping;

final class Argument
{
    private From $from;

    private function __construct(From $from)
    {
        $this->from = $from;
    }

    public static function create(From $from) : self
    {
        return new self($from);
    }

    public function from() : From
    {
        return $this->from;
    }
}
