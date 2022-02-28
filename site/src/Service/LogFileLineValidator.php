<?php

namespace App\Service;
//use App\Service\LogFileEntryAnalyser;

class LogFileLineValidator
{
	const POSSIBLE_STATUS_CODES=[100,101,102,200,201,202,203,204,205,206,207,208,226,300,301,302,303,304,305,307,308,400,401,402,403,404,405,406,407,408,409,410,411,412,413,414,415,416,417,418,421,422,423,424,426,428,429,431,444,451,499,500,501,502,503,504,505,506,507,508,510,511,599];

	private $log_file_entry_analyser;

	public function __construct(
        LogFileEntryAnalyser $log_file_entry_analyser
    )
    {
        $this->log_file_entry_analyser=$log_file_entry_analyser;
    }

	public function validateService($line)
	{
		$service=$this->log_file_entry_analyser->getService($line);
		if(
			$service!="" && 
			strtoupper($service)===$service && 
			str_ends_with($service,"-SERVICE")
		)
		return $service;
		return false;
	}

	public function validateDateTime($line)
	{
		$date=$this->log_file_entry_analyser->getDateTime($line);
		if(
			strtotime($date)
		)
		return $date;
		return false;
	}

	public function validateMethod($line)
	{
		$http_methods=["GET","HEAD","POST","PUT","DELETE","CONNECT","OPTIONS","TRACE","PATCH",];	
		$method=$this->log_file_entry_analyser->getMethod($line);
		if(
			in_array($method, $http_methods)
		)
		return $method;
		return false;
	}

	public function validateUrl($line)
	{
		$url=$this->log_file_entry_analyser->getUrl($line);
		if(
			preg_match("#^/(.*)#", $url)
		)
		return $url;
		return false;
	}

	public function validateProtocol($line)
	{
		$http_protocols=["HTTP/0.9","HTTP/1.0","HTTP/1.1","HTTP/1.1 bis","HTTP/2","HTTP/3",];	
		$protocol=$this->log_file_entry_analyser->getProtocol($line);
		if(
			in_array($protocol, $http_protocols)
		)
		return $protocol;
		return false;
	}

	public function validateStatusCode($line)
	{
		$status_code=$this->log_file_entry_analyser->getStatusCode($line);
		if(
			in_array($status_code, self::POSSIBLE_STATUS_CODES)
		)
		return $status_code;
		return false;
	}

    public function validateLine($line)
    {
    	$service = self::validateService($line);
		$date_time = self::validateDateTime($line);
		$method = self::validateMethod($line);
		$url = self::validateUrl($line);
		$protocol = self::validateProtocol($line);
		$status_code = self::validateStatusCode($line);

    	if(
    		$service &&
    		$date_time &&
    		$method &&
    		$url &&
    		$protocol &&
    		$status_code
    	)
        return [
        			'service'=>$service,
        			'date_time'=>$date_time,
    				'method'=>$method,
    				'url'=>$url,
    				'protocol'=>$protocol,
    				'status_code'=>$status_code
        		];
        return false;
    }
}