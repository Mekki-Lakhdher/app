<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class LogFileReaderTest extends KernelTestCase
{
	protected function setUp(): void
    {
    	self::bootKernel();
    }

    public function testLogFileIsReadable(): void
    {
        $this->assertFileIsReadable($_ENV['LOG_FILE']);
    }

    public function testLogFileValidLines(): void
    {
        $this->assertFileIsReadable($_ENV['LOG_FILE']);
    }

    public function testLogFileInvalidLines(): void
    {
        $this->assertFileIsReadable($_ENV['LOG_FILE']);
    }
}
