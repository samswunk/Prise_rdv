<?php

namespace App\Controller;

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
    /**
     * @Route("/new/", name="multi_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $form = $this->createForm(MultiType::class);
        $form->handleRequest($request);

        if ($request->isMethod('POST')) 
        {
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $data = $form->getData();
            } else {
                $form->submit($request->request->get($form->getName()));
                $entityManager = $this->getDoctrine()->getManager();
                $data = $form->getData();
            }
            dd($donees);
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

