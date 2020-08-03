<?php

namespace App\Controller;

use App\Repository\BookingRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RdvController extends AbstractController
{
 
     /**
     * @Route("/rdv/", name="rdv_index", methods={"GET"})
     */
    public function index(BookingRepository $bookingRepository): Response
    {
        $repos=$bookingRepository->findAll();
        $repos = $this->get('serializer')->serialize($repos, 'json');
        $repos=str_replace(":00+00:00","",$repos);
        $response = new Response($repos);
        $response->headers->set('Content-Type', 'application/json');
        return $response;        
    }
}

?>