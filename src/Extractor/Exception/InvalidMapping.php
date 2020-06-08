<?php

declare(strict_types=1);

namespace ObjectMapper\Extractor\Exception;

use InvalidArgumentException;

final class InvalidMapping extends InvalidArgumentException implements ExtractionError
{
}
