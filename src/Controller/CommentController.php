<?php

namespace App\Controller;

use App\Enum\Relations;
use App\Service\CommentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/comment')]
class CommentController extends AbstractController
{
    public function __construct(private CommentService $commentService)
    {}

    #[Route('/add/{relationType}/{relationId}', name: 'app_comment_add', methods: ['POST'])]
    public function addComment(
        Request $request,
        string $relationType,
        int $relationId
    ): Response
    {
        $content = $request->request->get('content');
        $relation = Relations::from($relationType);

        $this->commentService->createComment($content, $relation, $relationId);
        $this->addFlash('success', 'Comment added successfully!');

        // Redirect back to the entity page
        return $this->redirectToRoute($this->getRedirectRoute($relation), [
            $this->getRouteParam($relation) => $relationId
        ]);
    }

    #[Route('/view/{relationType}/{relationId}', name: 'app_comment_view')]
    public function viewComments(string $relationType, int $relationId): Response
    {
        $relation = Relations::from($relationType);
        $comments = $this->commentService->getCommentsByRelation($relation, $relationId);

        return $this->render('comment/showComments.html.twig', [
            'comments' => $comments,
            'relationType' => $relationType,
            'relationId' => $relationId,
        ]);
    }

    private function getRedirectRoute(Relations $relation): string
    {
        return match($relation) {
            Relations::EVENT => 'app_dashboard',
            Relations::POLL => 'app_poll_show',
            Relations::LISTITEM => 'app_item_show',
            Relations::LIST => 'app_itemlist_show',
            default => 'app_dashboard',
        };
    }

    private function getRouteParam(Relations $relation): string
    {
        return match($relation) {
            Relations::EVENT => 'eventId',
            Relations::POLL => 'pollId',
            Relations::LISTITEM => 'itemId',
            Relations::LIST => 'listId',
            default => 'id',
        };
    }
}

