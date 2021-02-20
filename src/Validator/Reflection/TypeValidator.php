<?php

declare(strict_types=1);

namespace ObjectMapper\Validator\Reflection;

use ObjectMapper\Validator\Context;
use ObjectMapper\Validator\Exception\UnprocessableData;
use ObjectMapper\Validator\Validator;

interface TypeValidator extends Validator
{
    /**
     * @throws UnprocessableData When $data is not an instance of TypeValidatorData.
     */
    public function validate(object $data, ?Context $context = null): Context;
}
