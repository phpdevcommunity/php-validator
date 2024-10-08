<?php

declare(strict_types=1);

namespace PhpDevCommunity\Validator\Assert;

use function filter_var;
use function is_string;

final class Url extends AbstractValidator
{
    /**
     * @var string
     */
    private string $message = '{{ value }} is not a valid URL.';

    public function validate($value): bool
    {
        if ($value === null) {
            return true;
        }

        if (!is_string($value)) {
            $this->error($this->message, ['value' => $value]);
            return false;
        }

        if (filter_var($value, FILTER_VALIDATE_URL) === false) {
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
