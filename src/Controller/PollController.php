<?php

namespace App\Controller;

use App\Entity\Poll;
use App\Enum\PollTypes;
use App\Enum\Relations;
use App\Form\PollFormType;
use App\Service\PollService;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PollController extends AbstractController
{

    public function __construct(private PollService $pollService)
    {
    }

    #[Route('/poll/create', name: 'app_poll_create')]
    public function create(Request $request): Response
    {
        $relation = $request->query->get('relation', Relations::GENERAL->value);
        $relationId = $request->query->get('relationId', 0);
        $pollType = $request->query->get('pollType', PollTypes::GENERAL->value);
        $question = $request->query->get('question');
        $returnRoute = $request->query->get('returnRoute');

        $poll = new Poll();
        if ($question) {
            $poll->setQuestion($question);
        }

        $form = $this->createForm(PollFormType::class, $poll);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $question = $form->get('question')->getData();
            /** @var Collection $pollOptions */
            $pollOptions = $form->get('options')->getData();
            $relation = Relations::tryFrom($relation);
            $pollType = PollTypes::tryFrom($pollType);
            $poll = $this->pollService->createPoll($question, $pollOptions->toArray(), $relation, $relationId, $pollType);

            $this->addFlash('success', 'Poll created successfully!');

            if ($returnRoute) {
                return $this->redirectToRoute($returnRoute);
            }

            return $this->redirectToRoute('app_poll_show', ['pollId' => $poll->getId()]);
        }

        return $this->render('poll/createPoll.html.twig', [
            'pollForm' => $form,
            'returnRoute' => $returnRoute
        ]);
    }



    #[Route('/poll/show/{pollId}', name: 'app_poll_show')]
    public function show(int $pollId): Response
    {
        $poll = $this->pollService->getPollById($pollId);

        return $this->render('poll/showPoll.html.twig', [
            'poll' => $poll,
        ]);
    }

    #[Route('/poll/vote/{pollId}', name: 'app_poll_vote', methods: ['POST'])]
    public function vote(Request $request, int $pollId): Response
    {
        $selectedPollOptions = $request->request->all();
        $this->pollService->vote($pollId, $selectedPollOptions, $this->getUser());
        $this->addFlash('success', 'Vote casted successfully!');
        return $this->redirectToRoute('app_poll_show', ['pollId' => $pollId]);
    }
}
