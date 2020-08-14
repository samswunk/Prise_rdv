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
        // $energie = $this->getEnergie();
        // $marque = $this->getMarque();
        $booking->setIdUser($user);
        // $booking->setIdUser($energie);
        // $booking->setIdUser($marque);
        $booking->setIsFree(false);
        
        $form = $this->createForm(RdvType::class, $booking);
        $form->handleRequest($request);
        // dd($request->request->all(),$form);
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
            $booking->setMarque(null);
            $booking->setEnergie(null);
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
        $admin = $this->security->isGranted('ROLE_ADMIN');

        if ($request->query->get('start')) 
        {
            $vstart= date('Y-m-d',strtotime($request->query->get('start')));
            $vend= date('Y-m-d',strtotime($request->query->get('end')));
            $repos=$bookingRepository->findByRange($vstart,$vend);
        }
        else
        {
            $repos=$bookingRepository->findAll();
        }
        foreach ($repos as $key=>$repo)
        {
            $title            = $repo->getIsFree() ? $repo->getTitle() : "RDV INDISPONIBLE";
            $description      = $repo->getIsFree() ? $repo->getDescription() : "";
            $bgColor          = $repo->getIsFree() ? "#407855" : "#D36D37"; //$repo->getBackGroundcolor();
            $textColor        = $repo->getIsFree() ? "#FFFFFF" : "#FFFFFF"; //$repo->getBackGroundcolor();
            $editable         = $repo->getIsFree() ? true : false;//$repo->getBackGroundcolor();
            $user             = $repo->getIdUser();
            if ($user) 
            {
                $title            = "RDV INDISPONIBLE"; // $repo->getTitle();
                $description      = ""; // $repo->getDescription();
                $bgColor          = "#D36D37";
                $editable         = "false";//$repo->getBackGroundcolor();
                if (($user->getId() == $CurrentUser->getId()) || $admin)
                {
                    $title            = $repo->getTitle();
                    $description      = $repo->getDescription();
                    $editable         = "true";//$repo->getBackGroundcolor();
                    $bgColor          ="#A4262C";
                    $data[$key]['idUser']   = $user->getId();
                    $data[$key]['nom']      = $user->getNom();
                    $data[$key]['email']    = $user->getEmail();
                    $data[$key]['telephone']= $user->getTelephone();
                    $data[$key]['adresse']  = $user->getAdresse() ." " . $user->getCodePostal() ." " . $user->getVille();
                }
            }
            $data[$key]['id']               = $repo->getId();
            $data[$key]['title']            = $title;
            $data[$key]['description']      = $description;
            $data[$key]['start']            = date_format($repo->getStart(),"Y-m-d H:i:s");
            $data[$key]['end']              = date_format($repo->getEnd(),"Y-m-d H:i:s");
            $data[$key]['editable']         = $editable;//$repo->getBackGroundcolor();
            $data[$key]['isFree']           = $repo->getIsFree();
            $data[$key]['backgroundColor']  =  $bgColor;
            $data[$key]['borderColor']      =  $bgColor;
            $data[$key]['textColor']        =  $textColor;
            $data[$key]['color']            =  "#FFFFFF";
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