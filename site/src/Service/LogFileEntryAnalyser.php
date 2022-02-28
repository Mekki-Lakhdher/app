<?php

namespace App\Service;

class LogFileEntryAnalyser
{
	public function getService($line)
	{
        if(str_contains($line," - - "))
        return explode(" - - ", $line)[0];
        return "";
	}

	public function getDateTime($line)
	{
		preg_match('#\[(.*?)\]#', $line, $match);
		if(isset($match[1]))
        return $match[1];
    	return null;
	}

	public function getMethod($line)
	{
		preg_match('#\"(.*?)\"#', $line, $match);
		if(isset($match[1]))
        return explode(" ", $match[1])[0];
    	return null;
	}

	public function getUrl($line)
	{
		preg_match('#\"(.*?)\"#', $line, $match);
		if(isset($match[1]))
        return explode(" ", $match[1])[1];
    	return null;
	}

	public function getProtocol($line)
	{
		preg_match('#\"(.*?)\"#', $line, $match);
		if(isset($match[1]))
        return explode(" ", $match[1])[2];
    	return null;
	}

	public function getStatusCode($line)
	{
		$data_array = array();
        preg_match('#(\d+)$#', $line, $data_array);
        if(isset($data_array[1]))
        return $data_array[1];
    	return null;
	}
}