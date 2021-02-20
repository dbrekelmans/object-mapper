<?php

declare(strict_types=1);

namespace ObjectMapper\Extractor;

final class StaticExtractor implements Extractor
{
    public function extract(object $source, string $data): string
    {
        return $data;
    }
}
