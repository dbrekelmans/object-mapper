<?php

declare(strict_types=1);

namespace ObjectMapper\Mapping;

use ObjectMapper\Extractor\Extractor;

final class From
{
    private Extractor $extractor;
    private string $target;

    private function __construct(Extractor $extractor, string $target)
    {
        $this->extractor = $extractor;
        $this->target = $target;
    }

    public static function create(Extractor $extractor, string $value) : self
    {
        return new self($extractor, $value);
    }

    public function extractor() : Extractor
    {
        return $this->extractor;
    }

    public function target() : string
    {
        return $this->target;
    }
}
