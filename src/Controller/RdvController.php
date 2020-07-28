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
        /*return $this->render('booking/index.html.twig', [
            'bookings' => $bookingRepository->findAll(),
        ]);*/
        $repos=$bookingRepository->findAll();
        
        $repos = $this->get('serializer')->serialize($repos, 'json');

        $response = new Response($repos);

        $response->headers->set('Content-Type', 'application/json');
        return $response;        
    }
    /**
     * @Route("/")
     */              
    public function number()
    {
        $number = random_int(0, 100);

        return $this->render('base.html.twig', [
            'number' => $number,
        ]);
    }
}

?>