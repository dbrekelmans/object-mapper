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
    public function extract(object $source, string $data)
    {
        try {
            $reflectionProperty = new ReflectionProperty(get_class($source), $data);
        } catch (ReflectionException $exception) {
            throw new NotAccessible(sprintf('Property "%s" does not exist.', $data));
        }

        if (!$reflectionProperty->isPublic()) {
            throw new NotAccessible(sprintf('Property "%s" is not public.', $data));
        }

        if (!$reflectionProperty->isInitialized($source)) {
            throw new NotAccessible(sprintf('Property "%s" is not initialized.', $data));
        }

        if ($reflectionProperty->isStatic()) {
            return $source::$$data;
        }

        return $source->$data;
    }
}
