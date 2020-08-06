<?php

namespace App\Controller;

use App\Repository\BookingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
* @Route("/rdv")
*/
class RdvController extends AbstractController
{
 
     /**
     * @Route("/", name="rdv_index", methods={"GET"})
     */
    public function index(BookingRepository $bookingRepository,Request $request): Response
    {
        $vstart= date('Y-m-d',strtotime($request->query->get('start')));
        $vend= date('Y-m-d',strtotime($request->query->get('end')));
        $repos=$bookingRepository->findByRange($vstart,$vend);
        // $repos=$bookingRepository->findAll();
        $repos = $this->get('serializer')->serialize($repos, 'json');
        $repos=str_replace(":00+00:00","",$repos);
        $response = new Response($repos);
        $response->headers->set('Content-Type', 'application/json');
        return $response;        
    }
}

?>