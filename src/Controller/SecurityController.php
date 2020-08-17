<?php

namespace App\Controller;

use App\Form\ResetPwdType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class SecurityController extends AbstractController
{
    
    private $rdv;

    public function __construct(RdvController $rdv)
    {
        $this->rdv = $rdv;
    }    

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    
    /**
     * @Route("/oubli", name="app_forgot")
     */
    public function forgot(Request $request,UserRepository $userRepository,TokenGeneratorInterface $tokenGeneratorInterface)
    {
        $form = $this->createForm(ResetPwdType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())   
        {
            $donnees = $form->getData();

            $user = $userRepository->findOneByEmail($donnees['email']);
            if(!$user) 
            {
                $this->addFlash('danger',"Cette adresse n'existe pas");
                return $this->redirectToRoute('app_forgot');
            }
            
            $token = $tokenGeneratorInterface->generateToken();
            try {
                $user->setResetToken($token);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning','Une erreur est survenue : '.$e->getMessage());
                $this->redirectToRoute('app_login');
            }

            $url = $this->generateUrl('app_reset',[
                'token'=>$token
            ],UrlGeneratorInterface::ABSOLUTE_URL);
            
            $this->rdv->envoiMail('reset',null,$user,null,null,$token,$url);

            $this->addFlash('success','Un email de réinitialisation a bien été envoyé à votre adresse.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/oubliPwd.html.twig',[
            'emailForm' =>$form->createView()
        ]);
    }

        /**
     * @Route("/reset/{token}", name="app_reset")
     */
    public function reset()
    {
        $form = $this->createForm(ResetPwdType::class);
        // $form->handleRequest($request);
        // if ($form->isSubmitted() && $form->isValid())   
        // {
    }

}
