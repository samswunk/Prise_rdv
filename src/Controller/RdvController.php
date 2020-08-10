<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RdvType;
use App\Entity\Booking;
use App\Form\BookingType;
use App\Repository\BookingRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
* @Route("/rdv")
*/
class RdvController extends AbstractController
{
 
    private $security;

    public function __construct(Security $security)
    {
            $this->security = $security;
    } 
    /**
     * Modification d'un rdv (pour un utilisateur, se positionner ou le modifier)
     * 
     * @Route("/{id}/edit", name="rdv_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Booking $booking): Response
    {
        $user = $this->getUser();
        $booking->setIdUser($user);
        
        $form = $this->createForm(RdvType::class, $booking);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $this->getDoctrine()->getManager()->flush();
            
            return $this->redirectToRoute('booking_index');
        }

        return $this->render('rdv/edit.html.twig', [
            'booking' => $booking,
            'form' => $form->createView(),
        ]);
    } 

    /**
     * @Route("/{id}", name="rdv_delete", methods={"GET","POST"})
     */
    public function delete(Request $request, Booking $booking): Response
    {
        $vMethod=$request->getMethod();
        // dd($booking);
        if ($request->isMethod('POST')) 
        {
            $start = $booking->getStart(); // new \DateTime(date("Y-m-d H:i:s",strtotime(str_replace('/', '-', $booking->getStart()))));
            $start = $start->format('d/m/Y H:i');
            $booking->setTitle('RDV DISPONIBLE '.$start);
            $booking->setDescription('');
            $booking->setIdUser(null);
            $booking->setIsFree(true);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('booking_index');
        }
        return $this->render('rdv/edit.html.twig', [
            'booking' => $booking,
            'form' => $form->createView(),
        ]);
    }

    /**
      * Afficher le calendrier => renvoie un JSON
      * 
     * @Route("/", name="rdv_index", methods={"GET"})
     */
    public function index(BookingRepository $bookingRepository,Request $request): Response
    {
        $CurrentUser = $this->security->getUser();
        if ($request->query->get('start')) 
        {
            $vstart= date('Y-m-d',strtotime($request->query->get('start')));
            $vend= date('Y-m-d',strtotime($request->query->get('end')));
            $repos=$bookingRepository->findByRange($vstart,$vend);
        }else{
            $repos=$bookingRepository->findAll();
        }
        foreach ($repos as $key=>$repo)
        {
            $data[$key]['title']            = $repo->getTitle();
            $data[$key]['start']            = date_format($repo->getStart(),"Y-m-d H:i:s");
            $data[$key]['end']              = date_format($repo->getEnd(),"Y-m-d H:i:s");
            $data[$key]['description']      = $repo->getDescription();
            $data[$key]['backgroundColor']  = "#54B796";//$repo->getBackGroundcolor();
            $data[$key]['id']               = $repo->getId();
            $user = $repo->getIdUser();
            if ($user) 
            {
                $bgColor="#DBACA9";
                if ($user->getId() == $CurrentUser->getId()) $bgColor="#FB8BA4";
                $data[$key]['backgroundColor']  =  $bgColor;
                $data[$key]['borderColor']      =  $bgColor;
                $data[$key]['idUser']   = $user->getId();
                $data[$key]['nom']      = $user->getNom();
                $data[$key]['email']    = $user->getEmail();
                $data[$key]['telephone']= $user->getTelephone();
                $data[$key]['adresse']  = $user->getAdresse() ." " . $user->getCodePostal() ." " . $user->getVille();
            }
        }
        // $data = json_decode($data);

        $response = new JsonResponse($data);
        // var_dump($response);
        // $repos=str_replace(":00+00:00","",$repos);
        $response->headers->set('Content-Type', 'application/json');
        return $response;        
   }

   /*        $serializer = new Serializer([new ObjectNormalizer()]);
        $repos = $serializer->normalize($repos, null, [AbstractNormalizer::ATTRIBUTES => ['id','title','start','end','description','backgroundColor','IdUser' => ['id','Nom','telephone','email']]]);
        // $result = $serializer->normalize($level1, null, [AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true]);
        $repos=str_replace(":00+00:00","",$repos);
        dd($vstart,$vend,$repos,$response);
        return $response;        
*/
}

?>