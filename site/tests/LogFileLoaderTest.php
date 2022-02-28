<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class LogFileLoaderTest extends KernelTestCase
{
	protected function setUp(): void
    {
    	self::bootKernel();
    }

    public function testLogFileIsResource(): void
    {
        $log_file = self::$kernel->getContainer()->get('log_file_loader')->loadLogFile();
        // return error if $log_file is not a file or is not readable or does not exist
        $this->assertIsResource($log_file);
    }

    public function testLogFileIsLogType(): void
    {
    	$file_extension=pathinfo($_ENV['LOG_FILE'])['extension'];
        $this->assertEquals('log',$file_extension);
    }
}
