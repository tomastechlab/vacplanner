<?php declare(strict_types=1);

namespace App\Service;

use App\Enum\Relations;
use App\Repository\CommentRepository;
use App\Entity\Comment;

class CommentService {

    public function __construct(private CommentRepository $commentRepository)
    {}

    public function getCommentsByRelation(Relations $relatedTo, int $relatedToId): array
    {
        return $this->commentRepository->findBy(['relatedTo' => $relatedTo->value, 'relatedToId' => $relatedToId]);
    }

    public function getCommentById(int $commentId): Comment
    {
        return $this->commentRepository->find($commentId);
    }

    public function createComment(string $content, Relations $relatedTo, int $relatedToId): Comment
    {
        $comment = new Comment();
        $comment->setComment($content);
        $comment->setRelatedTo($relatedTo->value);
        $comment->setRelatedToId($relatedToId);
        $this->commentRepository->persist($comment);
        $this->commentRepository->flush();
        return $comment;
    }
}
