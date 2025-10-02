<?php

namespace App\Controller;

use App\Entity\Event;
use App\Enum\PollTypes;
use App\Enum\Relations;
use App\Form\EventFormType;
use App\Service\PollService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Attribute\Route;

final class EventController extends AbstractController
{

    function __construct(
        private readonly HttpKernelInterface $httpKernel
    ){}

    #[Route('/event/create', name: 'app_event_create')]
    public function create(Request $request, EntityManagerInterface $entityManager, PollService $pollService): Response
    {
        $event = new Event();
        $form = $this->createForm(EventFormType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventData = $form->getData();
            $eventData->setCreator($this->getUser());

            $entityManager->persist($eventData);

            $entityManager->flush();
            if (!$eventData->getStartDate() && !$eventData->getEndDate()) {
                    $pollsToCreate[] = [
                        'question' => 'Wann findet ' . $eventData->getTitle() . ' statt?',
                        'pollType' => PollTypes::DATE->value
                    ];
                }

                if ($request->request->get('event_location_poll')) {
                    $pollsToCreate[] = [
                        'question' => 'Wo findet ' . $eventData->getTitle() . ' statt?',
                        'pollType' => PollTypes::LOCATION->value
                    ];
                }

                if (!empty($pollsToCreate)) {
                    $request->getSession()->set('polls_to_create', $pollsToCreate);
                    $request->getSession()->set('event_id', $eventData->getId());
                    return $this->redirectToRoute('app_event_handle_poll_creation');
                }
        }

        return $this->render('event/createEvent.html.twig', [
            'eventForm' => $form,
            'creator' => $this->getUser(),
        ]);
    }

    #[Route('/event/create-polls', name: 'app_event_handle_poll_creation')]
    public function createEventPolls(Request $request): Response
    {
        $pollsToCreate = $request->getSession()->get('polls_to_create', []);
        $eventId = $request->getSession()->get('event_id');

        if (empty($pollsToCreate)) {
            return $this->redirectToRoute('app_event_show', ['eventId' => $eventId]);
        }

        $currentPoll = array_shift($pollsToCreate);
        $request->getSession()->set('polls_to_create', $pollsToCreate);

        // Instead of creating the poll internally, redirect to the poll creation page
        return $this->redirectToRoute('app_poll_create', [
            'question' => $currentPoll['question'],
            'relation' => Relations::EVENT->value,
            'relationId' => $eventId,
            'pollType' => $currentPoll['pollType'],
            'returnRoute' => 'app_event_handle_poll_creation'
        ]);
    }

    #[Route('/event/show/{eventId}', name: 'app_event_show')]
    public function show(Request $request, EntityManagerInterface $entityManager, PollService $pollService, int $eventId): Response
    {
        $event = $entityManager->find(Event::class, $eventId);
        if( null === $event
            || ($event->isPrivate() && !$event->participants()->contains($this->getUser())))
        {
            $this->addFlash('error', 'Event not found');
            return $this->redirectToRoute('app_dashboard');
        }
        $datePoll = $pollService->getPollByRelation(Relations::EVENT, $eventId, PollTypes::DATE);
        $locationPoll = $pollService->getPollByRelation(Relations::EVENT, $eventId, PollTypes::LOCATION);
        return $this->render('event/showEvent.html.twig', [
            'event' => $event,
            'datePoll' => $datePoll,
            'locationPoll' => $locationPoll,
        ]);
    }

    #[Route('/event/edit/{eventId}', name: 'app_event_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, int $eventId): Response
    {
        return new Response('');
    }

    #[Route('/event/delete/{eventId}', name: 'app_event_delete')]
    public function delete(Request $request, EntityManagerInterface $entityManager, int $eventId): Response
    {
        return new Response('');
    }

    #[Route('/event/finish/{eventId}', name: 'app_event_finish')]
    public function finish(Request $request, EntityManagerInterface $entityManager, int $eventId): Response
    {
        return new Response('');
    }

}
