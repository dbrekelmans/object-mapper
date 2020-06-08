<?php

declare(strict_types=1);

namespace ObjectMapper\Extractor;

use ObjectMapper\Extractor\Exception\ExtractionError;

interface Extractor
{
    /**
     * @return mixed
     *
     * @throws ExtractionError
     */
    public function extract(object $from, string $target);
}
