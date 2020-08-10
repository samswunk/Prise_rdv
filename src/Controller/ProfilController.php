<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfilType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/profil")
 */
class ProfilController extends AbstractController
{
    /**
    * @Route("/{id}/edit", name="profil_edit", methods={"GET","POST"})
    */
    public function editProfil(Request $request, User $profil): Response
    {
        
        // dd(($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN') ? "Tout va bien" : "alerte !"));
        $TabProfil = $request->request->get('profil');

        $route = ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN') ? "profil_index" : "booking_index");
        $form = $this->createForm(ProfilType::class, $profil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            // dd($profil->getId(),$request->request->get('_token'));
            // if ($this->isCsrfTokenValid('update'.$profil->getId(), $request->request->get('_token'))) 
            // {
                if ($TabProfil["Nom"]) $profil->setNom($TabProfil['Nom']);
                if ($TabProfil["Prenom"]) $profil->setPrenom($TabProfil["Prenom"]);
                if ($TabProfil["Telephone"]) $profil->setTelephone($TabProfil["Telephone"]);
                if ($TabProfil["email"]) $profil->setEmail($TabProfil["email"]);
                if ($TabProfil["Adresse"]) $profil->setAdresse($TabProfil["Adresse"]);
                if ($TabProfil["CodePostal"]) $profil->setCodePostal($TabProfil["CodePostal"]);
                if ($TabProfil["Ville"]) $profil->setVille($TabProfil["Ville"]);
            // }
            // else {
            //     dd("erreur");
            // }    
            $entityManager->flush();
            // $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute($route);
        }

        return $this->render('profil/editProfil.html.twig', [
            'profil' => $profil,
            'form' => $form->createView(),
        ]);
    }

    // /**
    //  * @Route("/{id}/edit", name="profil_edit", methods={"GET","POST"})
    //  */
    // public function edit(Request $request, User $profil): Response
    // {
    //     $form = $this->createForm(ProfilType::class, $profil);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $this->getDoctrine()->getManager()->flush();

    //         return $this->redirectToRoute('profil_index');
    //     }

    //     return $this->render('profil/edit.html.twig', [
    //         'profil' => $profil,
    //         'form' => $form->createView(),
    //     ]);
    // }
}
