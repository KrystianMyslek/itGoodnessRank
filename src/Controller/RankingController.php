<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('ranking')]
class RankingController extends AbstractController
{

    public function __construct(private Security $security) {
        
    }

    #[Route('/list', name: 'ranking_list')]
    public function list(): Response
    {

        return $this->render('ranking/list.html.twig', [

        ]);
    }


    #[Route('/add', name: 'ranking_add_goodness')]
    public function add(): Response
    {

        return $this->render('ranking/add.html.twig', [

        ]);
    }


}