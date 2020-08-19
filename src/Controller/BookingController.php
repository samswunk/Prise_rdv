<?php

namespace App\Controller;

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
    private $rdv;

    public function __construct(RdvController $rdv)
    {
        $this->rdv = $rdv;
    }    
    /**
     * @Route("booking/", name="booking_index", methods={"GET","POST"})
     */
    public function index(BookingRepository $bookingRepository): Response
    {
        return $this->render('booking/calendar.html.twig', [
            'bookings' => $bookingRepository
            ->findBy(array(), array('start' => 'DESC')),
            // ->findAll(),
        ]);
    }

    /**
     * @Route("booking/valid", name="booking_valid", methods={"GET"})
     */
    public function valid(BookingRepository $bookingRepository): Response
    {
        
        $start = date("Y-m-d");
        /*$datetime = new \DateTime('tomorrow');
        echo $datetime->format('Y-m-d H:i:s');*/
        return $this->render('booking/index.html.twig', [
            'bookings' => $bookingRepository->findEventsToValidate($start)

            //->findBy(array(), array('start' => 'DESC')),
            // ->findAll(),
        ]);
    }

    /**
     * @Route("booking/list", name="booking_list", methods={"GET"})
     */
    public function list(BookingRepository $bookingRepository): Response
    {
        
        $start = date("Y-m-d");
        /*$datetime = new \DateTime('tomorrow');
        echo $datetime->format('Y-m-d H:i:s');*/
        return $this->render('booking/index.html.twig', [
            'bookings' => $bookingRepository
            ->findBy(array(), array('start' => 'DESC')),
            // ->findAll(),
        ]);
    }
    
    /**
     * CREATION d'un RDV par MODAL
     * 
     * @Route("booking/new_rdv", name="rdv_new", methods={"GET","POST"})
     */
    public function new_rdv(Request $request): Response
    {
        // dd($request->request->all());   
        $booking = new Booking();
        
        $id = $request->request->get('booking')['id'];
        $entityManager = $this->getDoctrine()->getManager();
        
        // si un id existe il s'agit d'une modification.
        if ($id) 
        {
            echo(" MODIFICATION DU RDV ".$id);
                $bookingRepository = $this->getDoctrine()
                    ->getRepository(Booking::class, 'default')
                    ->find($id);
                if ($bookingRepository) 
                {   $start = new \DateTime(date("Y-m-d H:i:s",strtotime(str_replace('/', '-', $request->request->get('booking')['start']))));
                    $end = new \DateTime(date("Y-m-d H:i:s",strtotime(str_replace('/', '-', $request->request->get('booking')['end']).":00")));
                    $bookingRepository->setStart($start);
                    $bookingRepository->setEnd($end);
                    $bookingRepository->setIsFree(true);
                    $bookingRepository->setIsConfirmed(false);
                    $entityManager->flush();
                    return $this->redirectToRoute('booking_index');
                }
        }
        echo(" CREATION D'UN RDV ".$id);
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);
        if ($form->isSubmitted()) 
        {
            $booking->setIsFree(true);
            $booking->setIsConfirmed(false);
            $entityManager->persist($booking);
            $entityManager->flush();
                /**/
        }
        return $this->redirectToRoute('booking_index'); 
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
        // $user = $this->getUser();
        // $booking->setIdUser($user);
        // dd($request);
        $form = $this->createForm(BookingType::class, $booking);

        $form->handleRequest($request);
        
        // dd($form,
        //     $booking
        // );
        if ($form->isSubmitted() && $form->isValid()) {
            
            $this->getDoctrine()->getManager()->flush();
            $rdv = $this->rdv;
            $typeEnvoi = ($request->request->get('booking')["isConfirmed"]==1 ? 'confirm' : 'cancel');
            if ($booking->getIdUser()) $rdv->envoiMail($typeEnvoi,$booking,$booking->getIdUser(),$booking->getMarque(),$booking->getEnergie());
            
            return $this->redirectToRoute('booking_list');
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
