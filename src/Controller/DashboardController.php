<?php

namespace App\Controller;

use App\Entity\Event;
use App\Enum\Relations;
use App\Service\CommentService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(EntityManagerInterface $entityManagerInterface, CommentService $commentService): Response
    {
        $entityRepository = $entityManagerInterface->getRepository(Event::class);
        $currentActiveEvents = $entityRepository->findBy(['active' => true]);
        $pastEvents = $entityRepository->findBy(['active' => false]);
        foreach ($pastEvents as $key => $pastEvent) {
            $comments = $commentService->getCommentsByRelation(Relations::EVENT, $pastEvent->getId());
            foreach ($comments as $comment) {
                $pastEvents[$key]->addComment($comment);
            }
        }
        return $this->render('dashboard/dashboard.html.twig', [
            'currentActiveEvents' => $currentActiveEvents,
            'pastEvents' => $pastEvents,
        ]);
    }
}
