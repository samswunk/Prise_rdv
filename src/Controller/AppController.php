<?php

namespace App\Controller;

use App\Repository\BookingRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
* @Route("/")
*/
class AppController extends AbstractController
{
     /**
     * @Route("/", name="app_index", methods={"GET"})
     */
    public function index(BookingRepository $bookingRepository): Response
    {
        return $this->render('index.html.twig', [
            'bookings' => $bookingRepository
            ->findBy(array(), array('start' => 'DESC')),
            // ->findAll(),
        ]);
        // return $this->render('booking/calendar.html.twig');
    }
}

?>