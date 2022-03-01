<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Log;

class LogEntryCountController extends AbstractController
{
    #[Route('/count', name: 'count')]
    public function index(Request $request,ManagerRegistry $doctrine): Response
    {
    	//?serviceNames[]=user&serviceNames[]=invoice&serviceNames[]=primary&startDate="17/08/2018 09:21:56"&endDate="17/08/2018 09:21:56"&statusCode=200
        $service_names=$request->query->get('serviceNames');
        $start_date=$request->query->get('startDate');
        $end_date=$request->query->get('endDate');
        $status_code=$request->query->get('statusCode');

        $repository = $doctrine->getRepository(Log::class);
        $result=$repository->countLogEntries($service_names,$start_date,$end_date,$status_code);

        $response = new Response();
        $response->setContent(json_encode([
            'counter' => intval($result['counter']),
        ]));
        return $response;

    }
}
