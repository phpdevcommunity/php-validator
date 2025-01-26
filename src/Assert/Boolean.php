<?php

declare(strict_types=1);

namespace PhpDevCommunity\Validator\Assert;

final class Boolean extends AbstractValidator
{
    private string $message = 'This value should be of type {{ type }}.';

    public function validate($value): bool
    {
        if ($value === null) {
            return true;
        }

        if (is_int($value) && ($value == 0 || $value === 1)) {
            return true;
        }

        if (is_bool($value) === false) {
            $this->error($this->message, ['value' => $value, 'type' => 'boolean']);
            return false;
        }

        return true;
    }

    public function message(string $message): self
    {
        $this->message = $message;
        return $this;
    }
}
