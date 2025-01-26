<?php

use PhpDevCommunity\Validator\Assert\Alphabetic;
use PhpDevCommunity\Validator\Assert\Alphanumeric;
use PhpDevCommunity\Validator\Assert\Boolean;
use PhpDevCommunity\Validator\Assert\Choice;
use PhpDevCommunity\Validator\Assert\Collection;
use PhpDevCommunity\Validator\Assert\Custom;
use PhpDevCommunity\Validator\Assert\Email;
use PhpDevCommunity\Validator\Assert\Integer;
use PhpDevCommunity\Validator\Assert\Item;
use PhpDevCommunity\Validator\Assert\NotEmpty;
use PhpDevCommunity\Validator\Assert\NotNull;
use PhpDevCommunity\Validator\Assert\Numeric;
use PhpDevCommunity\Validator\Assert\Psr7UploadFile;
use PhpDevCommunity\Validator\Assert\StringLength;
use PhpDevCommunity\Validator\Assert\Url;

if (!function_exists('v_alphabetic')) {
    function v_alphabetic(string $message = null): Alphabetic
    {
        $validator = new Alphabetic();
        if ($message !== null) {
            $validator->message($message);
        }
        return $validator;
    }
}

if (!function_exists('v_alphanumeric')) {
    function v_alphanumeric(string $message = null): Alphanumeric
    {
        $validator = new Alphanumeric();
        if ($message !== null) {
            $validator->message($message);
        }
        return $validator;
    }
}

if (!function_exists('v_boolean')) {
    function v_boolean(string $message = null): PhpDevCommunity\Validator\Assert\Boolean
    {
        $validator = new Boolean();
        if ($message !== null) {
            $validator->message($message);
        }
        return $validator;
    }
}


if (!function_exists('v_choice')) {
    function v_choice(array $choices, string $message = null): Choice
    {
        $validator = new Choice($choices);
        if ($message !== null) {
            $validator->message($message);
        }
        return $validator;
    }
}

if (!function_exists('v_collection')) {
    function v_collection(array $validators, string $message = null): Collection
    {
        $validator = new Collection($validators);
        if ($message !== null) {
            $validator->message($message);
        }
        return $validator;
    }
}

if (!function_exists('v_custom')) {
    function v_custom(callable $validate, string $message = null): Custom
    {
        $validator = new Custom($validate);
        if ($message !== null) {
            $validator->message($message);
        }
        return $validator;
    }
}


if (!function_exists('v_email')) {
    function v_email(string $message = null): Email
    {
        $validator = new Email();
        if ($message !== null) {
            $validator->message($message);
        }
        return $validator;
    }
}

if (!function_exists('v_integer')) {
    function v_integer(?int $min, ?int $max = null,string $invalidMessage = null): PhpDevCommunity\Validator\Assert\Integer
    {
        $validator = new Integer();
        if ($invalidMessage !== null) {
            $validator->invalidMessage($invalidMessage);
        }

        if ($min !== null) {
            $validator->min($min);
        }
        if ($max !== null) {
            $validator->max($max);
        }

        return $validator;
    }
}

if (!function_exists('v_item')) {
    function v_item(array $validators, string $message = null): Item
    {
        $validator = new Item($validators);
        if ($message !== null) {
            $validator->message($message);
        }
        return $validator;
    }
}

if (!function_exists('v_not_empty')) {
    function v_not_empty(string $message = null): NotEmpty
    {
        $validator = new NotEmpty();
        if ($message !== null) {
            $validator->message($message);
        }
        return $validator;
    }
}

if (!function_exists('v_not_null')) {
    function v_not_null(string $message = null): NotNull
    {
        $validator = new NotNull();
        if ($message !== null) {
            $validator->message($message);
        }
        return $validator;
    }
}


if (!function_exists('v_numeric')) {
    function v_numeric(string $message = null): Numeric
    {
        $validator = new Numeric();
        if ($message !== null) {
            $validator->message($message);
        }
        return $validator;
    }
}

if (!function_exists('v_psr7_upload_file')) {
    function v_psr7_upload_file(?int $maxSize, array $allowedMimeTypes, string $message = null): Psr7UploadFile
    {
        $validator = new Psr7UploadFile();
        if ($message !== null) {
            $validator->message($message);
        }
        if ($maxSize !== null) {
            $validator->maxSize($maxSize);
        }
        $validator->mimeTypes($allowedMimeTypes);
        return $validator;
    }
}

if (!function_exists('v_string_length')) {
    function v_string_length(?int $min, ?int $max = null, string $invalidMessage = null): StringLength
    {
        $validator = new StringLength();
        if ($invalidMessage !== null) {
            $validator->invalidMessage($invalidMessage);
        }

        if ($min !== null) {
            $validator->min($min);
        }
        if ($max !== null) {
            $validator->max($max);
        }

        return $validator;
    }
}

if (!function_exists('v_url')) {
    function v_url(string $message = null): Url
    {
        $validator = new Url();
        if ($message !== null) {
            $validator->message($message);
        }
        return $validator;
    }
}
