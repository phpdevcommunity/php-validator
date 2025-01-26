<?php

declare(strict_types=1);

namespace PhpDevCommunity\Validator\Assert;

use PhpDevCommunity\Validator\Validation;
use PhpDevCommunity\Validator\ValidationProcessor;
use function ctype_alpha;

final class Collection extends AbstractValidator
{
    private string $message = 'This value should be of type {{ type }}.';

    /**
     * @param array<ValidatorInterface> $validators
     */
    private array $validators;
    private array $errors = [];

    /**
     * @param array<ValidatorInterface> $validators
     */
    public function __construct(array $validators)
    {
        foreach ($validators as $validator) {
            if ($validator instanceof ValidatorInterface === false) {
                throw new \InvalidArgumentException(sprintf('The validator must be an instance of %s', ValidatorInterface::class));
            }
        }
        $this->validators = $validators;
    }
    public function validate($value): bool
    {
        if ($value === null) {
            return true;
        }

        if (is_array($value) === false) {
            $this->error($this->message, ['value' => $value, 'type' => 'collection']);
            return false;
        }

        $validationProcessor = new ValidationProcessor();
        $errors = [];
        foreach ($value as $key => $element) {
            $errors = array_merge($errors, $validationProcessor->process($this->validators, $key, $element));
        }

        if ($errors !== []) {
            $this->errors = $errors;
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
