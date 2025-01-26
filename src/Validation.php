<?php

namespace PhpDevCommunity\Validator;

use PhpDevCommunity\Validator\Assert\Collection;
use PhpDevCommunity\Validator\Assert\Item;
use PhpDevCommunity\Validator\Assert\ValidatorInterface;
use InvalidArgumentException;
use Psr\Http\Message\ServerRequestInterface;
use function array_map;
use function array_merge;
use function get_class;
use function gettype;
use function is_array;
use function is_object;
use function is_string;
use function sprintf;
use function trim;

final class Validation
{
    private ValidationProcessor $processor;

    /**
     * @var array<string,array>
     */
    private array $validators;

    /**
     * @var array<string,string>
     */
    private array $errors = [];

    private array $data = [];

    public function __construct(array $fieldValidators)
    {
        $this->processor = new ValidationProcessor();

        foreach ($fieldValidators as $field => $validators) {
            if (!is_array($validators)) {
                $validators = [$validators];
            }
            if (!is_string($field)) {
                throw new InvalidArgumentException('The field name must be a string');
            }
            $this->addValidator($field, $validators);
        }
    }

    /**
     * Validate the server request data.
     *
     * @param ServerRequestInterface $request The server request to validate
     * @return bool
     */
    public function validate(ServerRequestInterface $request): bool
    {
        $data = array_map(function ($value) {
            if (is_string($value) && empty(trim($value))) {
                return null;
            }
            return $value;
        }, array_merge($request->getParsedBody(), $request->getUploadedFiles()));

        return $this->validateArray($data);
    }

    /**
     * Validate an array of data using a set of validators.
     *
     * @param array $data The array of data to be validated
     * @return bool
     */
    public function validateArray(array $data): bool
    {
        $this->data = $data;
        $this->executeValidators($this->validators,  $this->data);
        return $this->getErrors() === [];
    }

    private function executeValidators(array $validatorsByField, array &$data): void
    {
        /**
         * @var $validators array<ValidatorInterface>
         */
        foreach ($validatorsByField as $field => $validators) {
            if (!isset($data[$field])) {
                $data[$field] = null;
            }

            $errors = $this->processor->process($validators, $field, $data[$field]);
            $this->errors = array_merge($this->errors, $errors);
        }
    }

    /**
     * @return array<string,string>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Add a validator for a specific field.
     *
     * @param string $field The field to validate
     * @param array<ValidatorInterface> $validators The validators to apply
     * @return void
     */
    private function addValidator(string $field, array $validators): void
    {
        foreach ($validators as $validator) {
            if (!$validator instanceof ValidatorInterface) {
                throw new InvalidArgumentException(sprintf(
                    $field . ' validator must be an instance of ValidatorInterface, "%s" given.',
                    is_object($validator) ? get_class($validator) : gettype($validator)
                ));
            }

            $this->validators[$field][] = $validator;
        }
    }
}
