<?php

namespace App\Controller;

use App\Entity\Goodness;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RankingController extends AbstractController
{
    #[Route('/')]
    public function list(): Response
    {

        return $this->render('ranking/list.html.twig', [

        ]);
    }


    #[Route('/add')]
    public function add(): Response
    {

        return $this->render('ranking/add.html.twig', [

        ]);
    }


}