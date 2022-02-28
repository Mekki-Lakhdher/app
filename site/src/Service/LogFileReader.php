<?php

namespace App\Service;

class LogFileReader
{
    public function readNewLine($fp)
    {
        return stream_get_line($fp, 1024 * 1024, "\n");
    }
}