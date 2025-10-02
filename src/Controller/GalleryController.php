<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\ImageUploadFormType;
use App\Service\ImageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GalleryController extends AbstractController
{

    function __construct(private ImageService $imageService)
    {
    }

    #[Route('/gallery/{galleryId}', name: 'app_gallery', methods: ['GET'])]
    public function index(Request $request, int|null $galleryId = null): Response
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 10);
        list($images, $total) = $this->imageService->getImagesFromGallery($galleryId, $page, $limit);
        if ($total == 0) {
            return $this->render('gallery/no_gallery.html.twig',[
                'galleryId' => $galleryId
            ]);
        }
        return $this->render('gallery/gallery.html.twig', [
            'galleryId' => $galleryId,
            'images' => $images,
            'total' => $total,
        ]);
    }

    #[Route('/gallery/{galleryId}/upload', name: 'app_gallery_image_upload')]
    public function uploadImage(Request $request, int $galleryId): Response
    {
        $image = new Image();
        $form = $this->createForm(ImageUploadFormType::class, $image);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedImage = $form->get('image')->getData();
            $this->imageService->uploadImage($uploadedImage, $galleryId);
            if ($uploadedImage) {
                return $this->json(['message' => 'Image uploaded successfully']);
            }
        }

        return $this->render('gallery/upload/upload.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/gallery/{galleryId}/{imageId}/delete', name: 'app_gallery_image_delete', methods: ['POST'])]
    public function deleteImage(int $galleryId, int $imageId): Response
    {
        $this->imageService->deleteImage($galleryId, $imageId);
        return $this->json(['message' => 'Image deleted successfully']);
    }

     #[Route('/gallery/{galleryId}/{imageId}', name: 'app_gallery_image_show', methods: ['GET'])]
    public function image(int $galleryId, int $imageId): Response
    {
        $image = $this->imageService->getImageById($galleryId, $imageId);
        return $this->render('gallery/image/image.html.twig', [
            'image' => $image,
        ]);
    }
}
