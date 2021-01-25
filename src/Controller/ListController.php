<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ListController extends AbstractController
{
    /**
      * @Route("/maliste")
    */
    public function MaListe()
    {
        $number = random_int(0, 100);

        return $this->render('list/malist.html.twig', [
            'number' => $number,
        ]);
    }
}
