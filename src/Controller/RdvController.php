<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RdvController extends AbstractController
{
    /**
     * @Route("/")
     */              
    public function number()
    {
        $number = random_int(0, 100);

        return $this->render('base.html.twig', [
            'number' => $number,
        ]);
    }
}

?>