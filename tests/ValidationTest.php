<?php

namespace Test\PhpDevCommunity\Validator;

use PhpDevCommunity\UniTester\TestCase;
use PhpDevCommunity\Validator\Assert\Alphabetic;
use PhpDevCommunity\Validator\Assert\Choice;
use PhpDevCommunity\Validator\Assert\Collection;
use PhpDevCommunity\Validator\Assert\Custom;
use PhpDevCommunity\Validator\Assert\Email;
use PhpDevCommunity\Validator\Assert\Integer;
use PhpDevCommunity\Validator\Assert\Item;
use PhpDevCommunity\Validator\Assert\NotEmpty;
use PhpDevCommunity\Validator\Assert\NotNull;
use PhpDevCommunity\Validator\Assert\Numeric;
use PhpDevCommunity\Validator\Assert\StringLength;
use PhpDevCommunity\Validator\Assert\Url;
use PhpDevCommunity\Validator\Validation;
use Test\PhpDevCommunity\Validator\Helper\Request;

class ValidationTest extends TestCase
{

    protected function setUp(): void
    {
        // TODO: Implement setUp() method.
    }

    protected function tearDown(): void
    {
        // TODO: Implement tearDown() method.
    }

    protected function execute(): void
    {
        $this->testOk();
        $this->testError();
        $this->testPersonValidation();
        $this->testEmailValidation();
        $this->testTagsValidation();
        $this->testArticlesValidation();
    }

    public function testOk()
    {
        $validation = new Validation([
            'email' => [new NotNull(), new Email()],
            'password' => new NotNull(),
            'firstname' => [new NotNull(), (new StringLength())->min(3), new Alphabetic()],
            'lastname' => [(new StringLength())->min(3)],
            'gender' => new Choice(['Mme', 'Mr', null]),
            'website' => [new NotNull(), new Url()],
            'age' => [new NotNull(), (new Integer())->min(18)],
            'invoice_total' => [new NotNull(), new Numeric()],
            'active' => [new NotNull(), new Custom(function ($value) {
                return is_bool($value);
            })]
        ]);

        $this->assertTrue($validation->validate(Request::create([
            'email' => 'dev@phpdevcommunity.com',
            'password' => 'Mypassword',
            'firstname' => 'phpdevcommunity',
            'lastname' => null,
            'gender' => 'Mr',
            'website' => 'https://www.phpdevcommunity.com',
            'age' => 20,
            'invoice_total' => '2000.25',
            'active' => true,
        ])));
    }

    public function testError()
    {
        $validation = new Validation([
            'email' => [new NotNull(), new Email()],
            'password' => new NotNull(),
            'firstname' => [new NotNull(), (new StringLength())->min(3), new Alphabetic()],
            'lastname' => [(new StringLength())->min(3)],
            'website' => [new NotNull(), new Url()],
            'invoice_total' => [new NotNull(), new Numeric()],
            'active' => [new NotNull(), new Custom(function ($value) {
                return is_bool($value);
            })]
        ]);

        $this->assertFalse($validation->validate(Request::create([
            'email' => 'dev@phpdevcommunity',
            'password' => null,
            'firstname' => '12',
            'lastname' => '12',
            'website' => 'www.phpdevcommunity',
            'invoice_total' => 'test2000.25',
            'active' => 'yes',
        ])));


        $this->assertStrictEquals(7, count($validation->getErrors()));
        $errors = $validation->getErrors();
        $this->assertStrictEquals(2, count($errors['firstname']));

        $this->assertStrictEquals($errors['email'][0], 'dev@phpdevcommunity is not a valid email address.');
        $this->assertStrictEquals($errors['active'][0], '"yes" is not valid');
    }
    public function testPersonValidation(): void
    {
        $validation = new Validation([
            'person' => [new NotEmpty(), new Item([
                'first_name' => [new NotNull(), new Alphabetic(), (new StringLength())->min(3)],
                'last_name' => [new NotNull(), new Alphabetic(), (new StringLength())->min(3)],
            ])]
        ]);

        $input = [
            'person' => [
                'first_name' => 'John',
                'last_name' => 'Doe'
            ]
        ];

        $result = $validation->validate(Request::create($input));
        $this->assertTrue($result);
        $this->assertEmpty($validation->getErrors());

        $invalidInput = [
            'person' => [
                'first_name' => '',
                'last_name' => null
            ]
        ];

        $result = $validation->validate(Request::create($invalidInput));
        $this->assertFalse($result);
        $this->assertNotEmpty($validation->getErrors());
    }

    public function testEmailValidation(): void
    {
        $validation = new Validation([
            'email' => [new NotNull(), new Email()]
        ]);

        $input = ['email' => 'test@example.com'];
        $result = $validation->validate(Request::create($input));
        $this->assertTrue($result);
        $this->assertEmpty($validation->getErrors());

        $invalidInput = ['email' => 'invalid-email'];
        $result = $validation->validate(Request::create($invalidInput));
        $this->assertFalse($result);
        $this->assertNotEmpty($validation->getErrors());
    }

    public function testTagsValidation(): void
    {
        $validation = new Validation([
            'tags' => [new NotEmpty(), new Collection([
                (new StringLength())->min(3)
            ])]
        ]);

        $input = ['tags' => ['tag1', 'tag2', 'tag3']];
        $result = $validation->validate(Request::create($input));
        $this->assertTrue($result);
        $this->assertEmpty($validation->getErrors());

        $invalidInput = ['tags' => ['a', 'bc']];
        $result = $validation->validate(Request::create($invalidInput));
        $this->assertFalse($result);
        $this->assertNotEmpty($validation->getErrors());
        $this->assertStrictEquals(2, count($validation->getErrors()));

    }

    public function testArticlesValidation(): void
    {
        $validation = new Validation([
            'articles' => [new NotEmpty(), new Collection([
                new Item([
                    'title' => [new NotEmpty(), (new StringLength())->min(3)],
                    'body' => [new NotNull(), (new StringLength())->min(3)],
                    'user' => new Item([
                        'email' => [new Email()]
                    ])
                ])
            ])]
        ]);

        $input = [
            'articles' => [
                [
                    'title' => 'Article 1',
                    'body' => 'This is the body of the article.',
                    'user' => ['email' => 'user1@example.com']
                ],
                [
                    'title' => 'Article 2',
                    'body' => 'Another body.',
                    'user' => ['email' => 'user2@example.com']
                ]
            ]
        ];

        $result = $validation->validate(Request::create($input));
        $this->assertTrue($result);
        $this->assertEmpty($validation->getErrors());

        $invalidInput = [
            'articles' => [
                [
                    'title' => '',
                    'body' => '',
                    'user' => ['email' => 'invalid-email']
                ],
                [
                    'title' => '',
                    'body' => '',
                    'user' => ['email' => 'invalid-email']
                ]
            ]
        ];

        $result = $validation->validate(Request::create($invalidInput));
        $this->assertFalse($result);
        $this->assertNotEmpty($validation->getErrors());
        $this->assertStrictEquals(6, count($validation->getErrors()));
    }

}
