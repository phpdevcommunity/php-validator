<?php

namespace PhpDevCommunity\Validator;

use PhpDevCommunity\Validator\Assert\Collection;
use PhpDevCommunity\Validator\Assert\Item;

final class ValidationProcessor
{

    public function process(array $validators, $field, $value):  array
    {
        $errors = [];

        foreach ($validators as $validator) {
            if ($validator->validate($value) === true) {
                continue;
            }
            if ($validator->getError()) {
                $errors[$field][] = $validator->getError();
            }

            if ($validator instanceof Item || $validator instanceof Collection) {
                foreach ($validator->getErrors() as $key => $error) {
                    $fullKey = sprintf('%s.%s', $field, $key);
                    $errors[$fullKey] = $error;
                }
            }
        }

        return $errors;
    }

}