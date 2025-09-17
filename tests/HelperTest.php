<?php

namespace Test\PhpDevCommunity\Validator;

use PhpDevCommunity\UniTester\TestCase;
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
use PhpDevCommunity\Validator\Validation;
use Test\PhpDevCommunity\Validator\Helper\Request;

class HelperTest extends TestCase
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
        $this->testVAlphabetic();
        $this->testVAlphanumeric();
        $this->testVBoolean();
        $this->testVChoice();
        $this->testVCollection();
        $this->testVCustom();
        $this->testVEmail();
        $this->testVInteger();
        $this->testVItem();
        $this->testVNotEmpty();
        $this->testVNotNull();
        $this->testVNumeric();
        $this->testVPsr7UploadFile();
        $this->testVStringLength();
        $this->testVUrl();
    }

    public function testVAlphabetic()
    {
        $validator = v_alphabetic('Custom error message');
        $validator->validate('123456');
        $this->assertInstanceOf(Alphabetic::class, $validator);
        $this->assertEquals('Custom error message', $validator->getError());
    }

    public function testVAlphanumeric()
    {
        $validator = v_alphanumeric('Custom message');
        $validator->validate('ue$ueue');
        $this->assertInstanceOf(Alphanumeric::class, $validator);
        $this->assertEquals('Custom message', $validator->getError());
    }

    public function testVBoolean()
    {
        $validator = v_boolean('Invalid boolean');
        $validator->validate(-1);
        $this->assertInstanceOf(Boolean::class, $validator);
        $this->assertEquals('Invalid boolean', $validator->getError());
    }
    public function testVChoice()
    {
        $choices = ['yes', 'no'];
        $validator = v_choice($choices, 'Invalid choice');
        $validator->validate('non');
        $this->assertInstanceOf(Choice::class, $validator);
        $this->assertEquals('Invalid choice', $validator->getError());
    }


    public function testVCollection()
    {
        $validator = v_collection([v_alphabetic(), v_numeric()], 'Invalid collection');
        $validator->validate('["123", "456"]');
        $this->assertInstanceOf(Collection::class, $validator);
        $this->assertEquals('Invalid collection', $validator->getError());
    }

    public function testVCustom()
    {
        $validator = v_custom(function($value) { return is_string($value); }, 'Invalid custom validation');
        $validator->validate([]);
        $this->assertInstanceOf(Custom::class, $validator);
        $this->assertEquals('Invalid custom validation', $validator->getError());
    }

    public function testVEmail()
    {
        $validator = v_email('Invalid email');
        $validator->validate('testnote@.com');
        $this->assertInstanceOf(Email::class, $validator);
        $this->assertEquals('Invalid email', $validator->getError());
    }

    public function testVInteger()
    {
        $validator = v_integer(10, 100, 'Invalid integer');
        $validator->validate(100.25);
        $this->assertInstanceOf(Integer::class, $validator);
        $this->assertEquals('Invalid integer', $validator->getError());
    }

    public function testVItem()
    {
        $validator = v_item([
            'email' => v_alphabetic(),
            'password' => v_numeric()
        ], 'Invalid item');
        $validator->validate('');
        $this->assertInstanceOf(Item::class, $validator);
        $this->assertEquals('Invalid item', $validator->getError());
    }

    public function testVNotEmpty()
    {
        $validator = v_not_empty('Cannot be empty');
        $validator->validate('');
        $this->assertInstanceOf(NotEmpty::class, $validator);
        $this->assertEquals('Cannot be empty', $validator->getError());
    }

    public function testVNotNull()
    {
        $validator = v_not_null('Cannot be null');
        $validator->validate(null);
        $this->assertInstanceOf(NotNull::class, $validator);
        $this->assertEquals('Cannot be null', $validator->getError());
    }

    public function testVNumeric()
    {
        $validator = v_numeric('Invalid numeric value');
        $validator->validate('100.25€');
        $this->assertInstanceOf(Numeric::class, $validator);
        $this->assertEquals('Invalid numeric value', $validator->getError());
    }

    public function testVPsr7UploadFile()
    {
        $validator = v_psr7_upload_file(100000, ['image/jpeg'], 'Invalid file upload instance');
        $validator->validate('test.jpg');
        $this->assertInstanceOf(Psr7UploadFile::class, $validator);
        $this->assertEquals('Invalid file upload instance', $validator->getError());
    }

    public function testVStringLength()
    {
        $validator = v_string_length(5, 100, 'String length invalid');
        $validator->validate(12345);
        $this->assertInstanceOf(StringLength::class, $validator);
        $this->assertEquals('String length invalid', $validator->getError());
    }

    public function testVUrl()
    {
        $validator = v_url('Invalid URL');
        $validator->validate('www.phpdevcommunity.com');
        $this->assertInstanceOf(Url::class, $validator);
        $this->assertEquals('Invalid URL', $validator->getError());
    }

}
