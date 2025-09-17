
# PHP Validator

PHP Validator is a fast, extensible, and simple PHP validation library that allows you to easily validate various types of data.

## Installation

You can install this library via [Composer](https://getcomposer.org/). Ensure your project meets the minimum PHP version requirement of 7.4.

```bash
composer require phpdevcommunity/php-validator
```

## Requirements

- PHP version 7.4 or higher
- Required package for PSR-7 HTTP Message (e.g., `guzzlehttp/psr7`)

## Usage

The PHP Validator library enables you to validate data in a simple and flexible manner using pre-configured validation rules. Here are some usage examples:

### Example 1: Email Address Validation

Validate an email address to ensure it is not null and matches the standard email format.

```php
use PhpDevCommunity\Validator\Validation;
use PhpDevCommunity\Validator\Assert\NotNull;
use PhpDevCommunity\Validator\Assert\Email;

// Instantiate Validation object for email validation
$validation = new Validation([
    'email' => [new NotNull(), new Email()]
]);

// Example data array
$data = ['email' => 'john.doe@example.com'];

// Validate the data
if ($validation->validateArray($data) === true) {
    echo "Email is valid!";
} else {
    $errors = $validation->getErrors();
    echo "Validation errors: " . implode(", ", $errors['email']);
}
```

### Example 2: Age Validation

Validate the age to ensure it is a non-null integer and is 18 or older.

```php
use PhpDevCommunity\Validator\Validation;
use PhpDevCommunity\Validator\Assert\NotNull;
use PhpDevCommunity\Validator\Assert\Integer;

// Instantiate Validation object for age validation
$validation = new Validation([
    'age' => [new NotNull(), new Integer(['min' => 18])]
]);

// Example data array
$data = ['age' => 25];

// Validate the data
if ($validation->validateArray($data) === true) {
    echo "Age is valid!";
} else {
    $errors = $validation->getErrors();
    echo "Validation errors: " . implode(", ", $errors['age']);
}
```

### Example 3: Converting Empty Strings to `null`

By default, when using `validate(ServerRequestInterface $request)`, the **convertEmptyToNull** option is automatically enabled.
This ensures that all empty strings (`""`) are converted to `null` before validation.

When using `validateArray(array $data)` directly, you need to enable this option manually:

```php
use PhpDevCommunity\Validator\Validation;
use PhpDevCommunity\Validator\Assert\NotNull;
use PhpDevCommunity\Validator\Assert\Item;
use PhpDevCommunity\Validator\Assert\Alphabetic;

// Define validation rules
$validation = new Validation([
    'person' => [new NotNull(), new Item([
        'first_name' => [new NotNull(), new Alphabetic()],
        'last_name'  => [new NotNull(), new Alphabetic()],
    ])]
]);

// Example input with an empty string
$validInput = [
    'person' => [
        'first_name' => '',     // will be converted to null
        'last_name'  => 'Doe'
    ]
];

// Manually enable conversion of "" to null
$validation->convertEmptyToNull();

if ($validation->validateArray($validInput) === true) {
    echo "Data is valid!";
} else {
    $errors = $validation->getErrors();
    echo "Validation errors: " . json_encode($errors, JSON_PRETTY_PRINT);
}
```

In this example:

* Before validation, empty strings are converted to `null`.
* This prevents validators such as `NotNull()` from failing because of an empty string.
* When using `validate($request)` with a PSR-7 request, this option is automatically enabled.


### Additional Examples

Let's explore more examples covering various validators:

#### Username Validation

Ensure that a username is not null, has a minimum length of 3 characters, and contains only alphanumeric characters.

```php
use PhpDevCommunity\Validator\Validation;
use PhpDevCommunity\Validator\Assert\NotNull;
use PhpDevCommunity\Validator\Assert\Alphanumeric;
use PhpDevCommunity\Validator\Assert\StringLength;

// Instantiate Validation object for username validation
$validation = new Validation([
    'username' => [new NotNull(), new StringLength(['min' => 3]), new Alphanumeric()]
]);

// Example data array
$data = ['username' => 'john_doe123'];

// Validate the data
if ($validation->validateArray($data) === true) {
    echo "Username is valid!";
} else {
    $errors = $validation->getErrors();
    echo "Validation errors: " . implode(", ", $errors['username']);
}
```

#### Example: Validating Nested Objects with `Item`

The `Item` rule allows you to validate nested objects or associative arrays with specific rules for each key.

```php
use PhpDevCommunity\Validator\Validation;
use PhpDevCommunity\Validator\Assert\NotNull;
use PhpDevCommunity\Validator\Assert\Item;
use PhpDevCommunity\Validator\Assert\StringLength;
use PhpDevCommunity\Validator\Assert\Alphabetic;

// Define validation rules for a nested object (e.g., a "person" object)
$validation = new Validation([
    'person' => [new NotNull(), new Item([
        'first_name' => [new NotNull(), new Alphabetic(), (new StringLength())->min(3)],
        'last_name' => [new NotNull(), new Alphabetic(), (new StringLength())->min(3)],
    ])]
]);

// Example data
$data = [
    'person' => [
        'first_name' => 'John',
        'last_name' => 'Doe'
    ]
];

// Validate the data
if ($validation->validateArray($data) === true) {
    echo "Person object is valid!";
} else {
    $errors = $validation->getErrors();
    echo "Validation errors: " . json_encode($errors, JSON_PRETTY_PRINT);
}
```

#### Example: Validating Arrays of Items with `Collection`

The `Collection` rule is used to validate arrays where each item in the array must satisfy a set of rules.

```php
use PhpDevCommunity\Validator\Validation;
use PhpDevCommunity\Validator\Assert\NotEmpty;
use PhpDevCommunity\Validator\Assert\Collection;
use PhpDevCommunity\Validator\Assert\Item;
use PhpDevCommunity\Validator\Assert\NotNull;
use PhpDevCommunity\Validator\Assert\StringLength;

// Define validation rules for a collection of articles
$validation = new Validation([
    'articles' => [new NotEmpty(), new Collection([
        new Item([
            'title' => [new NotNull(), (new StringLength())->min(3)],
            'body' => [new NotNull(), (new StringLength())->min(10)],
        ])
    ])]
]);

// Example data
$data = [
    'articles' => [
        ['title' => 'Article 1', 'body' => 'This is the body of the first article.'],
        ['title' => 'Article 2', 'body' => 'Second article body here.']
    ]
];

// Validate the data
if ($validation->validateArray($data) === true) {
    echo "Articles are valid!";
} else {
    $errors = $validation->getErrors();
    echo "Validation errors: " . json_encode($errors, JSON_PRETTY_PRINT);
}
```

#### URL Validation

Validate a URL to ensure it is not null and is a valid URL format.

```php
use PhpDevCommunity\Validator\Validation;
use PhpDevCommunity\Validator\Assert\NotNull;
use PhpDevCommunity\Validator\Assert\Url;

// Instantiate Validation object for URL validation
$validation = new Validation([
    'website' => [new NotNull(), new Url()]
]);

// Example data array
$data = ['website' => 'https://example.com'];

// Validate the data
if ($validation->validateArray($data) === true) {
    echo "Website URL is valid!";
} else {
    $errors = $validation->getErrors();
    echo "Validation errors: " . implode(", ", $errors['website']);
}
```

#### Numeric Value Validation

Validate a numeric value to ensure it is not null and represents a valid numeric value.

```php
use PhpDevCommunity\Validator\Validation;
use PhpDevCommunity\Validator\Assert\NotNull;
use PhpDevCommunity\Validator\Assert\Numeric;

// Instantiate Validation object for numeric value validation
$validation = new Validation([
    'price' => [new NotNull(), new Numeric()]
]);

// Example data array
$data = ['price' => 99.99];

// Validate the data
if ($validation->validateArray($data) === true) {
    echo "Price is valid!";
} else {
    $errors = $validation->getErrors();
    echo "Validation errors: " . implode(", ", $errors['price']);
}
```

#### Custom Validation Rule

Implement a custom validation rule using a callback function.

```php
use PhpDevCommunity\Validator\Validation;
use PhpDevCommunity\Validator\Assert\NotNull;
use PhpDevCommunity\Validator\Assert\Custom;

// Custom validation function to check if the value is a boolean
$isBoolean = function ($value) {
    return is_bool($value);
};

// Instantiate Validation object with a custom validation rule
$validation = new Validation([
    'active' => [new NotNull(), new Custom($isBoolean)]
]);

// Example data array
$data = ['active' => true];

// Validate the data
if ($validation->validateArray($data) === true) {
    echo "Value is valid!";
} else {
    $errors = $validation->getErrors();
    echo "Validation errors: " . implode(", ", $errors['active']);
}
```

Certainly! Below is a chapter you can add to your README specifically covering examples for using the `validate(ServerRequestInterface $request)` method from your `Validation` class.

## Using `validate(ServerRequestInterface $request)` Method

The `Validation` class provides a convenient method `validate(ServerRequestInterface $request)` to validate data extracted from a `\Psr\Http\Message\ServerRequestInterface` object. This method simplifies the process of validating request data typically received in a web application.

### Example 1: Validating User Registration Form

Suppose you have a user registration form with fields like `username`, `email`, `password`, and `age`. Here's how you can use the `validate` method to validate this form data:

```php
use PhpDevCommunity\Validator\Validation;
use PhpDevCommunity\Validator\Assert\NotNull;
use PhpDevCommunity\Validator\Assert\Email;
use PhpDevCommunity\Validator\Assert\Integer;

// Define validation rules for each field
$validation = new Validation([
    'username' => [new NotNull()],
    'email' => [new NotNull(), new Email()],
    'password' => [new NotNull()],
    'age' => [new NotNull(), new Integer()]
]);

// Assume $request is the \Psr\Http\Message\ServerRequestInterface object containing form data
if ($validation->validate($request) === true) {
    // Validation passed, retrieve validated data
    $validatedData = $validation->getData();
    // Process registration logic here (e.g., save to database)
    // $username = $validatedData['username'];
    // $email = $validatedData['email'];
    // $password = $validatedData['password'];
    // $age = $validatedData['age'];
    echo "Registration successful!";
} else {
    // Validation failed, retrieve validation errors
    $errors = $validation->getErrors();
    // Handle validation errors (e.g., display error messages to the user)
    echo "Validation errors: " . implode(", ", $errors);
}
```

In this example:
- We instantiate a `Validation` object with validation rules defined for each field (`username`, `email`, `password`, `age`).
- We call the `validate` method with the `$request` object containing form data.
- If validation passes (`validate` method returns `true`), we retrieve the validated data using `$validation->getData()` and proceed with the registration logic.
- If validation fails, we retrieve the validation errors using `$validation->getErrors()` and handle them accordingly.

### Example 2: Validating API Input Data

Consider validating input data received via an API endpoint. Here's how you can use the `validate` method in this context:

```php
use PhpDevCommunity\Validator\Validation;
use PhpDevCommunity\Validator\Assert\NotNull;
use PhpDevCommunity\Validator\Assert\Numeric;

// Define validation rules for API input data
$validation = new Validation([
    'product_id' => [new NotNull(), new Numeric()],
    'quantity' => [new NotNull(), new Numeric()]
]);

// Assume $request is the \Psr\Http\Message\ServerRequestInterface object containing API input data
if ($validation->validate($request) === true) {
    // Validation passed, proceed with processing API request
    $validatedData = $validation->getData();
    // Extract validated data
    $productId = $validatedData['product_id'];
    $quantity = $validatedData['quantity'];
    echo "API request validated successfully!";
} else {
    // Validation failed, retrieve validation errors
    $errors = $validation->getErrors();
    // Handle validation errors (e.g., return error response to the client)
    echo "Validation errors: " . implode(", ", $errors);
}
```

In this example:
- We define validation rules for `product_id` and `quantity`.
- We call the `validate` method with the `$request` object containing API input data.
- If validation passes, we retrieve the validated data using `$validation->getData()` and proceed with processing the API request.
- If validation fails, we retrieve the validation errors using `$validation->getErrors()` and handle them appropriately.

---

### Additional Features

- **Simple Interface**: Easily define validation rules using a straightforward interface.
- **Extensible**: Extend the library with custom validation rules by implementing the `ValidatorInterface`.
- **Error Handling**: Retrieve detailed validation errors for each field.

---

## License

This library is open-source software licensed under the [MIT license](LICENSE).

