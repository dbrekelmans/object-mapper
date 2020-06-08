<?php

declare(strict_types=1);

namespace ObjectMapper\Mapper;

use ObjectMapper\Extractor\Extractor;

final class MapperFactory
{
    private Extractor $extractor;

    public function __construct(Extractor $extractor)
    {
        $this->extractor = $extractor;
    }

    public function constructor() : ConstructorMapper
    {
        return new ConstructorMapper($this->extractor);
    }
}
