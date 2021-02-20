<?php

declare(strict_types=1);

namespace ObjectMapper\Validator;

use ObjectMapper\Validator\Exception\UnprocessableData;

interface Validator
{
    /**
     * @throws UnprocessableData When data object can not be processed.
     */
    public function validate(object $data, ?Context $context = null): Context;
}
