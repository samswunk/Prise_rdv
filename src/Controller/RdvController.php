<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RdvType;
use App\Entity\Marque;
use App\Entity\Booking;
use App\Entity\Energie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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

    public function envoiMail($typeEnvoi, ?Booking $booking, ?User $user, ?Marque $marque, ?Energie $energie,$token=null,$url='')
    {
        switch($typeEnvoi)
        {
            case 'delete':
                $libEnvoi="annulation";
                $objEnvoi=strtoupper($libEnvoi)." de votre rendez-vous chauffatec.fr";
            break;
            case 'edit':
                $libEnvoi="mail";
                $objEnvoi="Modification rendez-vous chauffatec.fr";
            break;
            case 'create':
                $libEnvoi="mail";
                $objEnvoi="Demande de rendez-vous chauffatec.fr";
            break;
            case 'confirm':
                $libEnvoi="confirmation";
                $objEnvoi=strtoupper($libEnvoi)." de votre rendez-vous chauffatec.fr";
            break;
            case 'cancel':
                $libEnvoi="avalidation";
                $objEnvoi="Annulation de rendez-vous.";
            break;
            case 'reset':
                $libEnvoi="reset";
                $objEnvoi="Réinitialisation de mot de passe.";
                break;
        }
        $marque     = $marque ? $marque->getNomMarque() : '' ;
        $tarif      = $energie ? $energie->getTarifEnergie().' €' : '' ;
        $energie    = $energie ? $energie->getNomEnergie() : '';
        $contact    = $user->getNom().' '.$user->getPrenom();
        $telephone  = $user->getTelephone();
        $adresse    = $user->getAdresse().' '.$user->getCodePostal().' '.$user->getVille();
        $emailDest  = $user->getEmail();
        $start      = $booking ? $booking->getStart()->format('d/m/Y H:i') : '';
        $end        = $booking ? $booking->getEnd()->format('d/m/Y H:i') : '';
        $msg = (new \Swift_Message($objEnvoi))
            // ->setFrom('contact@chauffatec.fr')
            ->setFrom('chauffatec@hotmail.fr')
            ->setTo($emailDest)
            ->setBody($this->renderView('mail/'.$libEnvoi.'.html.twig', [
                            'booking'   => $booking,
                            'contact'   => $contact,
                            'marque'    => $marque,
                            'tarif'     => $tarif,
                            'url'       => $url
                        ]),
                        'text/html'
                        );

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



   /*        $serializer = new Serializer([new ObjectNormalizer()]);
        $repos = $serializer->normalize($repos, null, [AbstractNormalizer::ATTRIBUTES => ['id','title','start','end','description','backgroundColor','IdUser' => ['id','Nom','telephone','email']]]);
        // $result = $serializer->normalize($level1, null, [AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true]);
        $repos=str_replace(":00+00:00","",$repos);
        dd($vstart,$vend,$repos,$response);
        return $response;        
*/
}

?>