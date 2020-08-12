<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\MultiType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("multi")
 */
class MultiController extends AbstractController
{
    
    public function getDateForSpecificDayBetweenDates($startDate,$endDate,$day_number): array
    {
        $date_array["startDate"]=$startDate;
        $date_array["endDate"]=$endDate;
        $date_array["day_number"]=$day_number;
        $endDate = strtotime($endDate);
        $days=array('1'=>'Monday','2' => 'Tuesday','3' => 'Wednesday','4'=>'Thursday','5' =>'Friday','6' => 'Saturday','7'=>'Sunday');
        for($i = strtotime($days[$day_number], strtotime($startDate)); $i <= $endDate; $i = strtotime('+1 week', $i))
        $date_array[]=date('Y-m-d',$i);
        
        return $date_array;
    }
    /**
     * @Route("/new/", name="multi_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $form = $this->createForm(MultiType::class);
        $form->handleRequest($request);

        if ($request->isMethod('POST')) 
        {
            $vstart = $request->request->get('multi')['start']." ".$request->request->get('multi')['start_hour'];
            $vend = $request->request->get('multi')['end']." ".$request->request->get('multi')['end_hour'];
            $start2 = date("Y-m-d",strtotime(str_replace('/', '-', $vstart)));
            $end2 = date("Y-m-d ",strtotime(str_replace('/', '-', $vend)));
            $vstartHeure=$request->request->get('multi')['start_hour'];
            $vendHeure=$request->request->get('multi')['end_hour'];
            $txt = "Tous les ".$request->request->get('day');
            $txt .= " du " . $request->request->get('multi')['start'] ." au " . $request->request->get('multi')['end'];
            $txt .= " de " . $request->request->get('multi')['start_hour']." à ".$request->request->get('multi')['end_hour'];
            $TabJours=$this->getDateForSpecificDayBetweenDates(  $start2, $end2, $request->request->get('day'));
            $txt .= print_r($TabJours);
            $em = $this->getDoctrine()->getManager();
            foreach ($TabJours as $key => $jour)
            {
                $booking = new Booking;
                $booking->setTitle("RDV DISPONIBLE");
                $booking->setStart(new \DateTime(date("Y-m-d H:i:s",strtotime($jour." ".$vstartHeure))));
                $booking->setEnd(new \DateTime(date("Y-m-d H:i:s",strtotime($jour." ".$vendHeure))));
                $booking->setIsFree(true);
                $tab[$key]['start']  = new \DateTime(date("Y-m-d H:i:s",strtotime($jour." ".$vstartHeure)));
                $tab[$key]['end']    = new \DateTime(date("Y-m-d H:i:s",strtotime($jour." ".$vendHeure)));
                $em->persist($booking);
            }
            $em->flush();
            // dd($em->flush());
            // if ($form->isSubmitted() && $form->isValid()) {
            //     $entityManager = $this->getDoctrine()->getManager();
            //     $data = $form->getData();
            // } else {
            //     $form->submit($request->request->get($form->getName()));
            //     $data = $form->getData();
            // }
            // dd($donees);
            return $this->redirectToRoute('booking_index');
        }
        return $this->render('multi/multi.html.twig', [
            'controller_name' => 'MutliController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/", name="mutli_index")
     */
    public function index(Request $request)
    {
        $form = $this->createForm(MultiType::class);
        $form->handleRequest($request);
        
        return $this->render( 'multi/multi.html.twig', [
            'controller_name'   => 'MutliController',
            'form'              => $form->createView()
        ]);
    }

}

