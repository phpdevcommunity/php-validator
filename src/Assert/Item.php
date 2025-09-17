<?php

declare(strict_types=1);

namespace PhpDevCommunity\Validator\Assert;

use InvalidArgumentException;
use PhpDevCommunity\Validator\Validation;

final class Item extends AbstractValidator
{
    private string $message = 'This value should be of type {{ type }}.';
    private string $messageKeyNotExist = 'The key {{ value }} does not exist.';

    private Validation $validation;
    private array $errors = [];

    /**
     * @param array<string,ValidatorInterface[]> $validators
     */
    public function __construct(array $validators)
    {
        $this->validation  = new Validation($validators);
    }

    public function convertEmptyToNull(): self
    {
        $this->validation->convertEmptyToNull();
        return $this;
    }

    public function noConvertEmptyToNull(): self
    {
        $this->validation->noConvertEmptyToNull();
        return $this;
    }

    public function validate($value): bool
    {
        if ($value === null) {
            return true;
        }

        if (is_array($value) === false) {
            $this->error($this->message, ['value' => $value, 'type' => 'array']);
            return false;
        }

        $this->validation->validateArray($value);
        if ($this->validation->getErrors() !== []) {
            $this->errors = $this->validation->getErrors();
            return false;
        }

        return true;
    }

    public function message(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
