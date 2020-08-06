<?php

namespace App\Controller;

use DateTimeInterface;
use App\Entity\Booking;
use App\Form\BookingType;
use App\Repository\BookingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/")
 */
class BookingController extends AbstractController
{
    
    /**
     * @Route("booking/", name="booking_index", methods={"GET"})
     */
    public function index(BookingRepository $bookingRepository): Response
    {
        return $this->render('booking/index.html.twig', [
            'bookings' => $bookingRepository
            ->findBy(array(), array('start' => 'DESC')),
            // ->findAll(),
        ]);
    }
    
    /**
     * @Route("booking/new_rdv", name="rdv_new", methods={"GET","POST"})
     */
    public function new_rdv(Request $request): Response
    {
        $booking = new Booking();
        $id = $request->request->get('booking')['id'];
        $entityManager = $this->getDoctrine()->getManager();
        
        if ($id) 
            {
                $bookingRepository = $this->getDoctrine()
                    ->getRepository(Booking::class, 'default')
                    ->find($id);
                if ($bookingRepository) 
                {   //date_format ( DateTimeInterface $object , string $format )
                    echo("OLD DATE : ". date_format($bookingRepository->getStart(),"Y-m-d H:i:s"));
                    echo("NEW DATE : ". date("Y-m-d H:i:s",strtotime(str_replace('/', '-', $request->request->get('booking')['start']))));
                    // die();
                    $start = new \DateTime(date("Y-m-d H:i:s",strtotime(str_replace('/', '-', $request->request->get('booking')['start']))));
                    $end = new \DateTime(date("Y-m-d H:i:s",strtotime(str_replace('/', '-', $request->request->get('booking')['end']).":00")));
                    // echo("date : " . $start);
                    // echo("convert : ".date(strtotime($start)));
                    $bookingRepository->setStart($start);
                    $bookingRepository->setEnd($end);
                    $entityManager->flush();
                    return $this->redirectToRoute('app_index');
                }
            }

        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);
        // $data = json_encode($request->request->all(), true);
            // $form->submit(array_merge(['url' => null], $request->request->all()), false);
            if ($form->isSubmitted()) 
            {
                
                /*$txt = "<pre>";
                $txt .= $request->request->get('title');
                $txt .= $request->request->get('start');
                $txt .= $request->request->get('end');
                $txt .= $request->request->get('description');
                $txt .= $request->request->get('background_color');
                $txt .= "</pre>";
                dd($txt);
                /*    $txt = "<pre>";
                return $this->redirectToRoute('booking_index'); 
                $booking->setTitle($request->request->get('title'));
                
                // $date = date_create_from_format('d/m/Y H:i:s',$request->request->get('start'));
                // $date = date('d/m/Y H:i:s',$request->request->get('start'));
                // $date = date_create($request->request->get('start'));
                // $date = $request->request->get('start');
                $booking->setStart(date_create($request->request->get('start')));
                $booking->setEnd(date_create($request->request->get('end')));
                $booking->setDescription($request->request->get('description'));
                $booking->setBackgroundColor($request->request->get('background_color'));
                /**/
                $entityManager->persist($booking);
                $entityManager->flush();
                // return $this->redirectToRoute('booking_index'); 
                /**/
            }
        return $this->redirectToRoute('app_index'); 
    }
    /**
     * @Route("booking/new", name="booking_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($booking);
            $entityManager->flush();

            return $this->redirectToRoute('booking_index');
        }

        return $this->render('booking/new.html.twig', [
            'booking' => $booking,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("booking/{id}", name="booking_show", methods={"GET"})
     */
    public function show(Booking $booking): Response
    {
        return $this->render('booking/show.html.twig', [
            'booking' => $booking,
        ]);
    }

    /**
     * @Route("booking/{id}/edit", name="booking_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Booking $booking): Response
    {
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('booking_index');
        }

        return $this->render('booking/edit.html.twig', [
            'booking' => $booking,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("booking/{id}", name="booking_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Booking $booking): Response
    {
        if ($this->isCsrfTokenValid('delete'.$booking->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($booking);
            $entityManager->flush();
        }

        return $this->redirectToRoute('booking_index');
    }
}
