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
    	//?serviceNames[]=user&serviceNames[]=invoice&serviceNames[]=primary
        $serviceNames=$request->query->get('serviceNames');
        $startDate=$request->query->get('startDate');
        $endDate=$request->query->get('endDate');
        $statusCode=$request->query->get('statusCode');
        

        $repository = $doctrine->getRepository(Log::class);
        $counter=$repository->countLogEntries();

        dd($counter);

        return $this->render('log_entry_count/index.html.twig', [
            'controller_name' => 'LogEntryCountController',
        ]);
    }
}
