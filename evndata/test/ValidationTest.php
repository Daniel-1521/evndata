<?php

require 'vendor/autoload.php'; // Include Composer's autoloader

use PHPUnit\Framework\TestCase;

require_once 'validation.php';

class ValidationTest extends TestCase
{
    public function testValidateEmail()
    {
        // Valid email addresses
        $this->assertTrue(validate_email('test@example.com'));
        $this->assertTrue(validate_email('user.name+tag+sorting@example.com'));
        $this->assertTrue(validate_email('user_name@example.co.in'));

        // Invalid email addresses
        $this->assertFalse(validate_email('invalid-email'));
        $this->assertFalse(validate_email('test@.com'));
        $this->assertFalse(validate_email('test@domain'));
        $this->assertFalse(validate_email('test@domain..com'));
    }

    public function testValidatePhone()
    {
        // Valid phone numbers
        $this->assertTrue(validate_phone('1234567890'));
        $this->assertTrue(validate_phone('+123456789012')); // If international format is required, adjust regex accordingly

        // Invalid phone numbers
        $this->assertFalse(validate_phone('123'));
        $this->assertFalse(validate_phone('phone1234'));
        $this->assertFalse(validate_phone('!@#$%^&*()'));
        $this->assertFalse(validate_phone('12345678901234567890')); // Too long
    }

    public function testValidatePassword()
    {
        // Valid passwords
        $this->assertTrue(validate_password('P@ssw0rd!')); // Should pass with the updated function

        // Invalid passwords
        $this->assertFalse(validate_password('short'));        // Too short
        $this->assertFalse(validate_password('password'));     // No digits, no special characters
        $this->assertFalse(validate_password('1234567890'));   // No letters, no special characters
        $this->assertFalse(validate_password('password!!!'));  // No digits
    }


}
