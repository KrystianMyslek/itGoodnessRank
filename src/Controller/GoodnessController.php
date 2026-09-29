<?php

namespace App\Controller;

use App\Entity\Goodness;
use App\Form\GoodnessType;
use App\Model\GoodnessStatusEnum;
use App\Repository\GoodnessRepository;
use App\Repository\VoteRepository;
use App\Service\GoodnessService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('goodness')]
class GoodnessController extends AbstractController
{

	public function __construct(private Security $security)
	{
	}

	#[Route(['/ranking', '/'], name: 'goodness_ranking')]
	public function ranking(
		GoodnessRepository $goodness_repository,
		VoteRepository $vote_repository
	): Response {
		$user = $this->security->getUser();

		$goodness_list = $goodness_repository->findBy(['status' => GoodnessStatusEnum::Active]);
		$podium_list = $goodness_repository->findPodium();

		$vote_list = !empty($user) ? $vote_repository->findScoringByUserAndBindByGoodnessId($user) : [];

		return $this->render('goodness/ranking.html.twig', [
			'goodness_list' => $goodness_list,
			'podium_list'   => $podium_list,
			'vote_list'     => $vote_list,
		]);
	}

	#[Route('/list', name: 'goodness_list')]
	public function list(
		GoodnessRepository $goodness_repository,
	): Response {
		$goodness_list = $goodness_repository->findBy(['status' => GoodnessStatusEnum::Active]);

		return $this->render('goodness/list.html.twig', [
			'goodness_list' => $goodness_list,
		]);
	}

	#[Route('/add', name: 'add_goodness')]
	public function add(
		Request $request,
		GoodnessService $goodnessService,
	): Response {
		$goodness = new Goodness();

		$form = $this->createForm(GoodnessType::class, $goodness);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			if ($form->has('iconFile')) {
				$goodness = $goodnessService->setIcon($goodness, $form->get('iconFile')->getData());
			}

			$goodnessService->create($goodness);

			$this->addFlash(
				'success',
				'Pozycja została zapisana'
			);

			return $this->redirectToRoute('goodness_ranking');
		}

		return $this->render('goodness/add.html.twig', [
			'title' => 'Dodaj',
			'form'  => $form,
		]);
	}

	#[Route('/edit/{id<\d+>}', name: 'edit_goodness')]
	public function edit(
		Goodness $goodness,
		Request $request,
		GoodnessService $goodnessService,
	): Response {

		$form = $this->createForm(GoodnessType::class, $goodness);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			if ($form->has('iconFile')) {
				$goodness = $goodnessService->setIcon($goodness, $form->get('iconFile')->getData());
			}

			$goodnessService->update($goodness);

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
		GoodnessService $goodnessService,
	): Response {
		$goodnessService->delete($goodness);

		$this->addFlash(
			'success',
			'Pozycja została usunięta'
		);

		return $this->redirectToRoute('goodness_list');
	}

	#[Route('/propose', name: 'propose_goodness')]
	public function propose(
		Request $request,
		GoodnessService $goodnessService,
	): Response {

		$goodness = new Goodness();

		$form = $this->createForm(GoodnessType::class, $goodness);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			if ($form->has('iconFile')) {
				$goodness = $goodnessService->setIcon($goodness, $form->get('iconFile')->getData());
			}

			$goodnessService->createProposal($goodness);

			$this->addFlash(
				'success',
				'Pozycja została zaproponowana'
			);

			return $this->redirectToRoute('goodness_ranking');
		}

		return $this->render('goodness/add.html.twig', [
			'title' => 'Zaproponuj',
			'form'  => $form,
		]);
	}

	#[Route('/proposal_list', name: 'proposal_goodness_list')]
	public function proposalList(
		GoodnessRepository $goodness_repository,
	): Response {
		$goodness_list = $goodness_repository->findBy(['status' => GoodnessStatusEnum::Proposal]);

		return $this->render('goodness/proposal_list.html.twig', [
			'goodness_list' => $goodness_list,
		]);
	}

	#[Route('/approve/{id<\d+>}', name: 'approve_goodness')]
	public function approve(
		Goodness $goodness,
		GoodnessService $goodnessService,
	): Response {
		$goodnessService->approve($goodness);

		$this->addFlash(
			'success',
			'Pozycja została zatwierdzona'
		);

		return $this->redirectToRoute('proposal_goodness_list');
	}

}