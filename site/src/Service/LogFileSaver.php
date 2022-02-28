<?php

namespace App\Service;

use App\Entity\Log;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class LogFileSaver
{

	private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function saveNewLine($line_data)
    {
    	$date_immutable = new \DateTimeImmutable('@' . strtotime($line_data['date_time']));
        $line = new Log();
        $line->setService($line_data['service']);
        $line->setDateTime($date_immutable);
        $line->setMethod($line_data['method']);
        $line->setUrl($line_data['url']);
        $line->setProtocol($line_data['protocol']);
        $line->setStatusCode($line_data['status_code']);
        $this->em->persist($line);
        $this->em->flush();
    }
}