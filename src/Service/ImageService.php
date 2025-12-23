<?php declare(strict_types=1);

namespace App\Service;

use App\Enum\Relations;
use App\Entity\Image;
use App\Repository\ImageRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\String\Slugger\SluggerInterface;

class ImageService {

    final const IMAGE_UPLOAD_PATH = 'uploads/gallery/';

    public function __construct(
        private GalleryService $galleryService,
        private ImageRepository $imageRepository,
        private EntityManagerInterface $entityManagerInterface,
        private CommentService $commentService,
        private SluggerInterface $slugger,
        )
    {
    }

    public function uploadImage(UploadedFile $uploadedFile, int $galleryId): void
    {
        if (!$gallery = $this->galleryService->getGalleryById($galleryId)) {
            throw new NotFoundHttpException('gallery not found');
        }

        $image = new Image();
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename)->lower();
        $filename = $safeFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();

        $image->setFilename($filename);
        $image->setFilesize($uploadedFile->getSize());
        $image->setPath(self::IMAGE_UPLOAD_PATH . $galleryId . '/');
<<<<<<< HEAD
        $uploadedFile->move($image->getPath(), $image->getFilename());
=======
        $file = $uploadedFile->move($image->getPath(), $image->getFilename());
>>>>>>> ae5afe9df6bba8cff89586d0da02ca0c97c284e6
        $this->entityManagerInterface->persist($image);

        $gallery->addImage($image);
        $this->entityManagerInterface->persist($gallery);
        $this->entityManagerInterface->flush();
    }

    public function getImageById(int $galleryId, int $imageId): Image
    {
        $gallery = $this->galleryService->getGalleryById($galleryId);

        if (!$image = $gallery->getImageById($imageId)) {
            throw new NotFoundHttpException('image not found');
        }
        return $image;
    }

    public function getImageComments(int $galleryId, int $imageId): array
    {
        return $this->commentService->getCommentsByRelation(Relations::IMAGE, $imageId);
    }

    public function getImagesFromGallery(int $galleryId, $offset = 0, $limit = 10): array
    {
        $gallery = $this->galleryService->getGalleryById($galleryId);
        $criteria = new Criteria();
        $criteria->where($criteria->expr()->eq('gallery', $gallery))
                ->setFirstResult($offset * $limit - $limit)
                ->setMaxResults($limit);
        $images = $this->imageRepository->matching($criteria)->toArray();

        $criteria = new Criteria();
        $criteria->where($criteria->expr()->eq('gallery', $gallery));
        $total = $this->imageRepository->matching($criteria)->count();
        return [$images, $total];
    }

    public function deleteImage(int $galleryId, int $imageId): void
    {
        $gallery = $this->galleryService->getGalleryById($galleryId);
        if (!$image = $gallery->getImageById($imageId)) {
            throw new NotFoundHttpException('Image not found');
        }
        $this->entityManagerInterface->remove($image);
        $this->entityManagerInterface->flush();
    }
}
