<?php

namespace App\Controller;

use App\Repository\BookingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
* @Route("/liste")
*/
class ListeController extends AbstractController
{
 
    private $security;
    private $mailer;

    public function __construct(Security $security,\Swift_Mailer $mailer)
    {
            $this->security = $security;
            $this->mailer = $mailer;
    } 

    /**
      * Afficher le calendrier => renvoie un JSON
      * s
     * @Route("/rdv", name="rdv_index", methods={"GET"})
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