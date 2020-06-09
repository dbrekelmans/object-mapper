<?php

declare(strict_types=1);

namespace ObjectMapper\Mapping;

use ObjectMapper\Extractor\Extractor;

final class Source
{
    private Extractor $extractor;
    private string $data;

    private function __construct(Extractor $extractor, string $data)
    {
        $this->extractor = $extractor;
        $this->data = $data;
    }

    public static function create(Extractor $extractor, string $value) : self
    {
        return new self($extractor, $value);
    }

    public function extractor() : Extractor
    {
        return $this->extractor;
    }

    public function data() : string
    {
        return $this->data;
    }
}
