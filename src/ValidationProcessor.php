<?php

namespace PhpDevCommunity\Validator;

use PhpDevCommunity\Validator\Assert\Collection;
use PhpDevCommunity\Validator\Assert\Item;

final class ValidationProcessor
{
    private bool $convertEmptyToNull = false;

    public function process(array $validators, $field, $value):  array
    {
        $errors = [];

        foreach ($validators as $validator) {
            if ($this->convertEmptyToNull === true) {
                if ( empty($value) ) {
                    $value = null;
                }
            }
            $validatorIsItemOrCollection = ($validator instanceof Item || $validator instanceof Collection);
            if ($validatorIsItemOrCollection) {
                if ($this->convertEmptyToNull === true) {
                    $validator->convertEmptyToNull();
                }else {
                    $validator->noConvertEmptyToNull();
                }
            }

            if ($validator->validate($value) === true) {
                continue;
            }

            if ($validator->getError()) {
                $errors[$field][] = $validator->getError();
            }

            if ($validatorIsItemOrCollection) {
                foreach ($validator->getErrors() as $key => $error) {
                    $fullKey = sprintf('%s.%s', $field, $key);
                    $errors[$fullKey] = $error;
                }
            }
        }

        return $errors;
    }

    public function convertEmptyToNull(): void
    {
        $this->convertEmptyToNull = true;
    }

    public function noConvertEmptyToNull(): void
    {
        $this->convertEmptyToNull = false;
    }
}
