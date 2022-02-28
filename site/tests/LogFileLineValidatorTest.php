<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class LogFileLineValidatorTest extends KernelTestCase
{

    private $file;

	protected function setUp(): void
    {
    	self::bootKernel();
    }

    public function testValidateServiceEmptyLine()
    {
        $line='';
        $invalid_service = self::$kernel->getContainer()->get('log_file_line_validator')->validateService($line);
        $this->assertFalse($invalid_service);
    }

    public function testValidateEmptyService()
    {
        $line=' - - [17/Aug/2018:09:21:53 +0000] "POST /users HTTP/1.1" 201';
        $invalid_service = self::$kernel->getContainer()->get('log_file_line_validator')->validateService($line);
        $this->assertFalse($invalid_service);
    }

    public function testValidateLowerCaseService()
    {
        $line='mekki-SERVICE - - [17/Aug/2018:09:21:53 +0000] "POST /users HTTP/1.1" 201';
        $invalid_service = self::$kernel->getContainer()->get('log_file_line_validator')->validateService($line);
        $this->assertFalse($invalid_service);
    }

    public function testValidateMalformattedService()
    {
        $line='MEKKISERVICE - - [17/Aug/2018:09:21:53 +0000] "POST /users HTTP/1.1" 201';
        $invalid_service = self::$kernel->getContainer()->get('log_file_line_validator')->validateService($line);
        $this->assertFalse($invalid_service);
    }

    public function testValidateWellformattedService()
    {
        $line='MEKKI-SERVICE - - [17/Aug/2018:09:21:53 +0000] "POST /users HTTP/1.1" 201';
        $valid_service = self::$kernel->getContainer()->get('log_file_line_validator')->validateService($line);
        $this->assertNotFalse($valid_service);
    }

    public function testValidateDateEmptyLine()
    {
        $line='';
        $invalid_date = self::$kernel->getContainer()->get('log_file_line_validator')->validateDateTime($line);
        $this->assertFalse($invalid_date);
    }

    public function testValidateEmptyDate()
    {
        $line=' - - [] "POST /users HTTP/1.1" 201';
        $invalid_date = self::$kernel->getContainer()->get('log_file_line_validator')->validateDateTime($line);
        $this->assertFalse($invalid_date);
    }

    public function testValidateAbsentDateContainer()
    {
        $line=' - -  "POST /users HTTP/1.1" 201';
        $invalid_date = self::$kernel->getContainer()->get('log_file_line_validator')->validateDateTime($line);
        $this->assertFalse($invalid_date);
    }

    public function testValidateMalpositionedDate()
    {
        $line=' - - []17/Aug/2018:09:21:53 +0000 "POST /users HTTP/1.1" 201';
        $invalid_date = self::$kernel->getContainer()->get('log_file_line_validator')->validateDateTime($line);
        $this->assertFalse($invalid_date);
    }

    public function testValidateMalformattedDate()
    {
        $line=' - - [17/Aug/2018:99:21:53 +0000] "POST /users HTTP/1.1" 201';
        $invalid_date = self::$kernel->getContainer()->get('log_file_line_validator')->validateDateTime($line);
        $this->assertFalse($invalid_date);
    }

    public function testValidateWellformattedDate()
    {
        $line=' - - [17/Aug/2018:09:21:53 +0000] "POST /users HTTP/1.1" 201';
        $valid_date = self::$kernel->getContainer()->get('log_file_line_validator')->validateDateTime($line);
        $this->assertNotFalse($valid_date);
    }

    public function testValidateMethodEmptyLine()
    {
        $line='';
        $invalid_method = self::$kernel->getContainer()->get('log_file_line_validator')->validateMethod($line);
        $this->assertFalse($invalid_method);
    }

    public function testValidateEmptyMethod()
    {
        $line=' - - [17/Aug/2018:99:21:53 +0000] " /users HTTP/1.1" 201';
        $invalid_method = self::$kernel->getContainer()->get('log_file_line_validator')->validateMethod($line);
        $this->assertFalse($invalid_method);
    }

    public function testValidateInvalidMethod()
    {
        $line=' - - [17/Aug/2018:99:21:53 +0000] "PUTCH /users HTTP/1.1" 201';
        $invalid_method = self::$kernel->getContainer()->get('log_file_line_validator')->validateMethod($line);
        $this->assertFalse($invalid_method);
    }

    public function testValidateValidMethod()
    {
        $line=' - - [17/Aug/2018:99:21:53 +0000] "DELETE /users HTTP/1.1" 201';
        $valid_method = self::$kernel->getContainer()->get('log_file_line_validator')->validateMethod($line);
        $this->assertNotFalse($valid_method);
    }

    public function testValidateUrlEmptyLine()
    {
        $line='';
        $invalid_url = self::$kernel->getContainer()->get('log_file_line_validator')->validateUrl($line);
        $this->assertFalse($invalid_url);
    }

    public function testValidateEmptyUrl()
    {
        $line=' - - [17/Aug/2018:99:21:53 +0000] "PUTCH  HTTP/1.1" 201';
        $invalid_url = self::$kernel->getContainer()->get('log_file_line_validator')->validateUrl($line);
        $this->assertFalse($invalid_url);
    }

    public function testValidateInvalidUrl()
    {
        $line=' - - [17/Aug/2018:99:21:53 +0000] "PUTCH m/ekki HTTP/1.1" 201';
        $invalid_method = self::$kernel->getContainer()->get('log_file_line_validator')->validateUrl($line);
        $this->assertFalse($invalid_method);
    }

    public function testValidateValidUrl()
    {
        $line=' - - [17/Aug/2018:99:21:53 +0000] "PUTCH /mekki HTTP/1.1" 201';
        $valid_method = self::$kernel->getContainer()->get('log_file_line_validator')->validateUrl($line);
        $this->assertNotFalse($valid_method);
    }

    public function testValidateProtocolEmptyLine()
    {
        $line='';
        $invalid_prptocol = self::$kernel->getContainer()->get('log_file_line_validator')->validateProtocol($line);
        $this->assertFalse($invalid_prptocol);
    }

    public function testValidateEmptyProtocol()
    {
        $line=' - - [17/Aug/2018:99:21:53 +0000] "PUTCH m/ekki " 201';
        $invalid_protocol = self::$kernel->getContainer()->get('log_file_line_validator')->validateProtocol($line);
        $this->assertFalse($invalid_protocol);
    }

    public function testValidateInvalidProtocol()
    {
        $line=' - - [17/Aug/2018:99:21:53 +0000] "PUTCH m/ekki HTTPX" 201';
        $invalid_protocol = self::$kernel->getContainer()->get('log_file_line_validator')->validateProtocol($line);
        $this->assertFalse($invalid_protocol);
    }

    public function testValidateValidProtocol()
    {
        $line=' - - [17/Aug/2018:99:21:53 +0000] "PUTCH m/ekki HTTP/1.1" 201';
        $valid_protocol = self::$kernel->getContainer()->get('log_file_line_validator')->validateProtocol($line);
        $this->assertNotFalse($valid_protocol);
    }

    public function testValidateStatusCodeEmptyLine()
    {
        $line='';
        $invalid_status_code = self::$kernel->getContainer()->get('log_file_line_validator')->validateStatusCode($line);
        $this->assertFalse($invalid_status_code);
    }

    public function testValidateEmptyStatusCode()
    {
        $line=' - - [17/Aug/2018:99:21:53 +0000] "PUTCH m/ekki HTTPX" ';
        $invalid_status_code = self::$kernel->getContainer()->get('log_file_line_validator')->validateStatusCode($line);
        $this->assertFalse($invalid_status_code);
    }

    public function testValidateInvalidStatusCode()
    {
        $line=' - - [17/Aug/2018:99:21:53 +0000] "PUTCH m/ekki HTTPX" 119';
        $invalid_status_code = self::$kernel->getContainer()->get('log_file_line_validator')->validateStatusCode($line);
        $this->assertFalse($invalid_status_code);
    }

    public function testValidateValidStatusCode()
    {
        $line=' - - [17/Aug/2018:99:21:53 +0000] "PUTCH m/ekki HTTPX" 400';
        $valid_status_code = self::$kernel->getContainer()->get('log_file_line_validator')->validateStatusCode($line);
        $this->assertNotFalse($valid_status_code);
    }

}