<?php

declare(strict_types=1);

namespace ObjectMapper\Extractor;

use ObjectMapper\Extractor\Exception\NotAccessible;
use ReflectionException;
use ReflectionProperty;
use function get_class;
use function sprintf;

final class PropertyExtractor implements Extractor
{
    /** @inheritDoc */
    public function extract(object $from, string $target)
    {
        try {
            $reflectionProperty = new ReflectionProperty(get_class($from), $target);
        } catch (ReflectionException $exception) {
            throw new NotAccessible(sprintf('Property "%s" does not exist.', $target));
        }

        if (!$reflectionProperty->isPublic()) {
            throw new NotAccessible(sprintf('Property "%s" is not public.', $target));
        }

        if (!$reflectionProperty->isInitialized($from)) {
            throw new NotAccessible(sprintf('Property "%s" is not initialized.', $target));
        }

        if ($reflectionProperty->isStatic()) {
            return $from::$$target;
        }

        return $from->$target;
    }
}
