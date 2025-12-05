<?php

namespace App\Controller;

use App\Entity\Goodness;
use App\Form\GoodnessType;
use App\Model\GoodnessStatusEnum;
use App\Repository\GoodnessRepository;
use App\Repository\VoteRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('goodness')]
class GoodnessController extends AbstractController
{

    public function __construct(private Security $security) {
    }
    
    #[Route('/ranking', name: 'goodness_ranking')]
    public function list(
        GoodnessRepository $goodness_repository,
        VoteRepository $vote_repository
        ): Response
    {
        $user = $this->security->getUser();

        $goodness_list = $goodness_repository->findBy(['status' => GoodnessStatusEnum::Active]);
        
        $vote_list = !empty($user) ? $vote_repository->findScoringByUserAndBindByGoodnessId($user) : [];

        return $this->render('goodness/ranking.html.twig', [
            'goodness_list' => $goodness_list,
            'vote_list' => $vote_list
        ]);
    }

    #[Route('/add', name: 'add_goodness')]
    public function add(
        Request $request,
        EntityManagerInterface $manager,
        FileUploader $fileUploader,
        #[Autowire('%app.uploadsPath%/goodness_icon')] string $iconDirectory,
    ): Response
    {

        $goodness = new Goodness();
        
        $form = $this->createForm(GoodnessType::class, $goodness);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $iconFile = $form->get('icon')->getData();

            if ($iconFile) {
                $fileUploader->setTargetDirectory($iconDirectory);
                $iconFilename = $fileUploader->upload($iconFile);
                $goodness->setIcon($iconFilename);
            }

            $goodness->setCreatedAt(new \DateTimeImmutable('now'));

            $manager->persist($goodness);
            $manager->flush();

            return $this->redirectToRoute('goodness_ranking');
        }

        return $this->render('goodness/add.html.twig', [
            'form' => $form,
        ]);
    }

}