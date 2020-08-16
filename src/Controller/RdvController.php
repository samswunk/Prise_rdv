<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RdvType;
use App\Entity\Marque;
use App\Entity\Booking;
use App\Entity\Energie;
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
    private $mailer;

    public function __construct(Security $security,\Swift_Mailer $mailer)
    {
            $this->security = $security;
            $this->mailer = $mailer;
    } 

    public function envoiMail($typeEnvoi, Booking $booking, User $user, ?Marque $marque, ?Energie $energie)
    {
        switch($typeEnvoi)
        {
            case 'delete':
                $libEnvoi="annulation";
                break;
            case 'edit':
                $libEnvoi="mail";
                break;
            case 'create':
                $libEnvoi="mail";
                break;
            case 'confirm':
                $libEnvoi="confirmation";
                break;
            case 'cancel':
                $libEnvoi="avalidation";
                break;
        }
        $marque     = $marque ? $marque->getNomMarque() : '' ;
        $tarif      = $energie ? $energie->getTarifEnergie().' €' : '' ;
        $energie    = $energie ? $energie->getNomEnergie() : '';
        $contact    = $user->getNom().' '.$user->getPrenom();
        $telephone  = $user->getTelephone();
        $adresse    = $user->getAdresse().' '.$user->getCodePostal().' '.$user->getVille();
        $emailDest  = $user->getEmail();
        $start      = $booking->getStart()->format('d/m/Y H:i');
        $end        = $booking->getEnd()->format('d/m/Y H:i');

        $message = '<html><body>
                    Le '.date("d/m/Y à H:i") .' '.$contact.' a '.$libEnvoi.' le rdv du ' .$start . ' : '
                    . '<br>Marque       : '.$marque
                    . '<br>Energie      : '.$energie
                    . '<br>Fin estimée  : '.$end
                    . '<br>Tarif estimé : '.$tarif
                    . '<br>Ce rendez-vous vous engage auprès du professionnel. Merci de prévenir  : '.$tarif
                    .'</body></html>';

        $msg = (new \Swift_Message('Objet'))
            ->setFrom('contact@chauffatec.fr')
            ->setTo($emailDest)
            ->setBody(  $this->renderView('mail/'.$libEnvoi.'.html.twig', [
                            'booking'   => $booking,
                            'contact'   => $contact,
                            'marque'    => $marque,
                            'tarif'     => $tarif
                        ]),
                        'text/html'
                        );
            // ->setBody(  $message,
            //             'text/html'
            //             );

        $this->mailer->send($msg);
    }

    /**
    * @Route("/{id}/valider", name="rdv_valider", methods={"GET","POST"})
    */
    public function Valider(Request $request, Booking $booking): Response
    {
        
        $booking->setIsConfirmed(1);
        $this->getDoctrine()->getManager()->flush();
        $this->envoiMail('confirm',$booking,$booking->getIdUser(),$booking->getMarque(),$booking->getEnergie());
        $this->addFlash('message',"Le rdv est confirmé, et un mail vient d'être envoyé");
        return $this->redirectToRoute('booking_list');
    }

    /**
     * @Route("/{id}/mail", name="mail", methods={"GET","POST"})
    */
    public function mail(Booking $booking): Response
    {
        return $this->render('mail/mail.html.twig', [
            'booking' => $booking,
        ]);
    }

    /**
     * Modification d'un rdv (pour un utilisateur, se positionner ou le modifier)
     * 
     * @Route("/{id}/edit", name="rdv_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Booking $booking): Response
    {
        // On cherche l'idUser du booking pour savoir s'il s'agit d'une création ou d'une modification 
        $IdUserPrec = $booking->getIdUser();

        $user = $this->getUser();
        $booking->setIdUser($user);
        $booking->setIsFree(false);
        
        $form = $this->createForm(RdvType::class, $booking);
        $form->handleRequest($request);
        $view = $form->getViewData();

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $this->envoiMail(($IdUserPrec?'edit':'create'),$booking,$user,$view->getMarque(),$view->getEnergie());
            
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('message',"un mail vient d'être envoyé");
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
            $this->envoiMail('delete',$booking, $booking->getIdUser(),$booking->getMarque(),$booking->getEnergie());
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
        ]);
    }

    /**
      * Afficher le calendrier => renvoie un JSON
      * 
     * @Route("/", name="rdv_index", methods={"GET"})
     */
    public function index(BookingRepository $bookingRepository,Request $request): Response
    {
        $CurrentUser    = $this->security->getUser();
        $admin          = $this->security->isGranted('ROLE_ADMIN');

        if (($request->query->get('start')) && !$admin) 
        {
            $vstart= date('Y-m-d',strtotime($request->query->get('start')));
            $vstart= date('Y-m-d');
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
            /* 
            * s'il y a un utilisateur enregistré (idUser !null) pour ce rdv il s'agit d'un rdv déjà pris
            */
            if ($user) 
            {
                $title            = "RDV INDISPONIBLE"; // $repo->getTitle();
                $description      = ""; // $repo->getDescription();
                $bgColor          = "#D36D37";
                $editable         = "false";//$repo->getBackGroundcolor();
                /* 
                * si un utilisateur est loggé (CurrentUser!=null) et que son id = à l'event chargé, l'utilisateur peut tout voir
                * OU
                * si l'utilisateur courrant est administrateur il peut tout voir.
                */
                if (    ( $CurrentUser && $user->getId() == $CurrentUser->getId() )  
                    || $admin)
                {
                    $title            = $repo->getTitle();
                    $description      = $repo->getDescription();
                    $editable         = "true";//$repo->getBackGroundcolor();
                    $bgColor          = "#A4262C";
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