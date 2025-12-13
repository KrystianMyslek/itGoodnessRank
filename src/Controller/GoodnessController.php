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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('goodness')]
class GoodnessController extends AbstractController
{

    public function __construct(private Security $security) {
    }
    
    #[Route('/ranking', name: 'goodness_ranking')]
    public function ranking(
        GoodnessRepository $goodness_repository,
        VoteRepository $vote_repository
    ): Response
    {
        $user = $this->security->getUser();

        $goodness_list = $goodness_repository->findBy(['status' => GoodnessStatusEnum::Active]);
        $podium_list = $goodness_repository->findPodium();
        
        $vote_list = !empty($user) ? $vote_repository->findScoringByUserAndBindByGoodnessId($user) : [];

        return $this->render('goodness/ranking.html.twig', [
            'goodness_list' => $goodness_list,
            'podium_list' => $podium_list,
            'vote_list' => $vote_list,
        ]);
    }
    
    #[Route('/list', name: 'goodness_list')]
    public function list(
        GoodnessRepository $goodness_repository,
    ): Response
    {
        $goodness_list = $goodness_repository->findBy(['status' => GoodnessStatusEnum::Active]);

        return $this->render('goodness/list.html.twig', [
            'goodness_list' => $goodness_list,
        ]);
    }

    #[Route('/add', name: 'add_goodness')]
    public function add(
        Request $request,
        EntityManagerInterface $manager,
        FileUploader $fileUploader,
    ): Response
    {

        $goodness = new Goodness();
        
        $form = $this->createForm(GoodnessType::class, $goodness);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $iconFile = $form->get('iconFile')->getData();
            if ($iconFile) {
                $iconFilename = $fileUploader->upload($iconFile, 'goodness_icon');
                
                $goodness->setIcon($iconFilename);
            }

            $goodness->setStatus(GoodnessStatusEnum::Active);
            $goodness->setCreatedAt(new \DateTimeImmutable('now'));
            
            $manager->persist($goodness);
            $manager->flush();

            $this->addFlash(
                'success',
                'Pozycja została zapisana'
            );

            return $this->redirectToRoute('goodness_ranking');
        }

        return $this->render('goodness/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/edit/{id<\d+>}', name: 'edit_goodness')]
    public function edit(
        Goodness $goodness,
        Request $request,
        EntityManagerInterface $manager,
        FileUploader $fileUploader,
    ): Response
    {

        $form = $this->createForm(GoodnessType::class, $goodness);
        $form->handleRequest($request);

        $iconFile = $form->get('iconFile')->getData();
        if ($iconFile) {
            $iconFilename = $fileUploader->upload($iconFile, 'goodness_icon');

            $goodness->setIcon($iconFilename);
        }

        if ($form->isSubmitted() && $form->isValid()) {

            $goodness->setCreatedAt(new \DateTimeImmutable('now'));

            $manager->flush();

            $this->addFlash(
                'success',
                'Pozycja została zaktualizowana'
            );

            return $this->redirectToRoute('goodness_list');
        }

        return $this->render('goodness/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id<\d+>}', name: 'delete_goodness')]
    public function delete(
        Goodness $goodness,
        EntityManagerInterface $manager,
    ): Response
    {
        $goodness->setStatus(GoodnessStatusEnum::Deleted);

        $manager->flush();

        $this->addFlash(
            'success',
            'Pozycja została usunięta'
        );

        return $this->redirectToRoute('goodness_list');
    }

}