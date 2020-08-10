<?php

namespace App\Controller;

use App\Repository\BookingRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
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
 
     /**
     * @Route("/", name="rdv_index", methods={"GET"})
     */
    public function index(BookingRepository $bookingRepository,Request $request): Response
    {
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
            if ($user) {
                $data[$key]['backgroundColor']  = "#DBACA9";
                $data[$key]['borderColor']      = "#DBACA9";
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