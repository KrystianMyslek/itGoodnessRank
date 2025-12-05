<?php

namespace App\Controller;

use App\Entity\Goodness;
use App\Entity\User;
use App\Entity\Vote;
use App\Model\VoteScoringEnum;
use App\Repository\GoodnessRepository;
use App\Repository\VoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('vote')]
class VoteController extends AbstractController
{
    private User $user;

    public function __construct(
        private Security $security,
        private EntityManagerInterface $manager
                
    ) {
        $this->user = $security->getUser();
    }

    #[Route('/vote', name: 'vote_goodness')]
    public function vote(
        Request $request,
        GoodnessRepository $goodness_repository,
        VoteRepository $vote_repository
    ): JsonResponse
    {
        
        $goodness_id = $request->get('goodness_id');
        $scoring_name = $request->get('scoring_name');
        
        if (empty($goodness_id)) {
            $response = $this->setResponse('error', 'goodness empty');
            return $this->json($response);
        } 
        
        if (empty($scoring_name)) {
            $response = $this->setResponse('error', 'scoring empty');
            return $this->json($response);
        } 

        $vote = $vote_repository->findOneBy([
            'goodness' => $goodness_id,
            'user' => $this->user->getId()
        ]);

        if (!empty($vote)) {
            if ($vote->getScoring()->name == $scoring_name) {
                $response = $this->setResponse('error', 'vote already exists');
                return $this->json($response);
            }

            $scoring = VoteScoringEnum::fromName($scoring_name);

            $vote->setScoring($scoring);
            $this->saveVote($vote);
            $response = $this->setResponse('success', 'vote updated');
        } else {
            $goodness = $goodness_repository->find($goodness_id);
            $scoring = VoteScoringEnum::fromName($scoring_name);
                
            $this->createVote($goodness, $this->user, $scoring);
            $response = $this->setResponse('success', 'vote added');
        }
        
        return $this->json($response);
    }
    
    public function createVote(Goodness $goodness, User $user, $scoring) : void {
        $vote = new Vote();
        $vote->setGoodness($goodness);
        $vote->setUser($user);
        $vote->setScoring($scoring);
        $vote->setCreatedAt(new \DateTimeImmutable('now'));
        
        $this->saveVote($vote);
    }

    public function saveVote(Vote $vote) : void {
        $this->manager->persist($vote);
        $this->manager->flush();
    }
    
    private function setResponse(string $status, string $message) : array {
        return [
            'status' => $status,
            'message' => $message
        ];
    }
}