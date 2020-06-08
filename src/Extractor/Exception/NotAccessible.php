<?php

declare(strict_types=1);

namespace ObjectMapper\Extractor\Exception;

use UnexpectedValueException;

final class NotAccessible extends UnexpectedValueException implements ExtractionError
{
}
