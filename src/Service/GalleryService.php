<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Event;
use App\Entity\Gallery;
use App\Enum\Relations;
use App\Repository\GalleryRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GalleryService {

    public function __construct(
        private GalleryRepository $galleryRepository,
        private CommentService $commentService
        )
    {
    }

    public function createGallery(Event $event): void
    {
        $gallery = new Gallery();
        $gallery->setEvent($event);
        $this->galleryRepository->save($gallery);
    }

    public function getGalleryById(int $id): Gallery
    {
        if(!$gallery = $this->galleryRepository->find($id)) {
            throw new NotFoundHttpException('Gallery not found');
        }
        return $gallery;
    }

     public function getGalleryComments(int $galleryId): array
    {
        return $this->commentService->getCommentsByRelation(Relations::GALLERY, $galleryId);
    }

}
