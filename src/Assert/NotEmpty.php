<?php

declare(strict_types=1);

namespace PhpDevCommunity\Validator\Assert;

final class NotEmpty extends AbstractValidator
{
    private string $message = 'This value should not be empty.';

    public function validate($value): bool
    {
        if (empty($value)) {
            $this->error($this->message, ['value' => $value]);
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
