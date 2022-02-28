<?php

namespace App\Service;

class LogFileLoader
{
    public function loadLogFile()
    {
        return fopen($_ENV['LOG_FILE'], 'r');
    }
}