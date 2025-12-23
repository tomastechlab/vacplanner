<?php

namespace App\Controller;

use App\Entity\ItemList;
use App\Entity\Item;
use App\Enum\Relations;
use App\Service\ListService;
use App\Service\CommentService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[Route('/list')]
class ListController extends AbstractController
{
    public function __construct(
        private ListService $listService,
        private CommentService $commentService,
        private EntityManagerInterface $entityManager
    ) {}

    #[Route('/', name: 'app_list_index')]
    public function index(): Response
    {
        $lists = $this->entityManager->getRepository(ItemList::class)->findAll();

        return $this->render('list/index.html.twig', [
            'lists' => $lists,
        ]);
    }

    #[Route('/create', name: 'app_list_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $name = $request->request->get('name');
            $relatedTo = $request->request->get('relatedTo', 'general');
            $relatedToId = (int) $request->request->get('relatedToId', 0);

            $relation = Relations::from($relatedTo);
            $list = $this->listService->createList($name, $relation, $relatedToId);

            $this->addFlash('success', 'List created successfully!');
            return $this->redirectToRoute('app_list_show', ['listId' => $list->getId()]);
        }

        return $this->render('list/createList.html.twig');
    }

    #[Route('/{listId}', name: 'app_list_show', requirements: ['listId' => '\d+'])]
    public function show(int $listId): Response
    {
        $list = $this->getListById($listId);
        $comments = $this->commentService->getCommentsByRelation(Relations::LIST, $listId);

        return $this->render('list/showList.html.twig', [
            'list' => $list,
            'comments' => $comments,
        ]);
    }

    #[Route('/{listId}/edit', name: 'app_list_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $listId): Response
    {
        $list = $this->getListById($listId);

        if ($request->isMethod('POST')) {
            $name = $request->request->get('name');
            $list->setListName($name);

            $this->entityManager->flush();

            $this->addFlash('success', 'List updated successfully!');
            return $this->redirectToRoute('app_list_show', ['listId' => $listId]);
        }

        return $this->render('list/edit.html.twig', [
            'list' => $list,
        ]);
    }

    #[Route('/{listId}/delete', name: 'app_list_delete', methods: ['POST'])]
    public function delete(int $listId): Response
    {
        $list = $this->getListById($listId);

        $this->entityManager->remove($list);
        $this->entityManager->flush();

        $this->addFlash('success', 'List deleted successfully!');
        return $this->redirectToRoute('app_list_index');
    }

    #[Route('/{listId}/item/add', name: 'app_list_item_add', methods: ['POST'])]
    public function addItem(Request $request, int $listId): Response
    {
        $list = $this->getListById($listId);
        $itemName = $request->request->get('itemName');

        if (empty($itemName)) {
            $this->addFlash('error', 'Item name cannot be empty!');
            return $this->redirectToRoute('app_list_show', ['listId' => $listId]);
        }

        $this->listService->addItemToList($list, $itemName);

        $this->addFlash('success', 'Item added successfully!');
        return $this->redirectToRoute('app_list_show', ['listId' => $listId]);
    }

    #[Route('/{listId}/item/{itemId}/edit', name: 'app_list_item_edit', methods: ['POST'])]
    public function editItem(Request $request, int $listId, int $itemId): Response
    {
        $list = $this->getListById($listId);
        $item = $this->getItemById($itemId);

        if ($item->getItemList()->getId() !== $list->getId()) {
            throw new NotFoundHttpException('Item does not belong to this list');
        }

        $newName = $request->request->get('itemName');
        if (empty($newName)) {
            $this->addFlash('error', 'Item name cannot be empty!');
            return $this->redirectToRoute('app_list_show', ['listId' => $listId]);
        }

        $item->setItemName($newName);
        $this->entityManager->flush();

        $this->addFlash('success', 'Item updated successfully!');
        return $this->redirectToRoute('app_list_show', ['listId' => $listId]);
    }

    #[Route('/{listId}/item/{itemId}/delete', name: 'app_list_item_delete', methods: ['POST'])]
    public function deleteItem(int $listId, int $itemId): Response
    {
        $list = $this->getListById($listId);
        $item = $this->getItemById($itemId);

        if ($item->getItemList()->getId() !== $list->getId()) {
            throw new NotFoundHttpException('Item does not belong to this list');
        }

        $this->entityManager->remove($item);
        $this->entityManager->flush();

        $this->addFlash('success', 'Item deleted successfully!');
        return $this->redirectToRoute('app_list_show', ['listId' => $listId]);
    }

    #[Route('/{listId}/assign-user', name: 'app_list_assign_user', methods: ['POST'])]
    public function assignUserToList(Request $request, int $listId): Response
    {
        $list = $this->getListById($listId);
        $userId = (int) $request->request->get('userId');

        $user = $this->entityManager->getRepository(\App\Entity\User::class)->find($userId);
        if (!$user) {
            $this->addFlash('error', 'User not found!');
            return $this->redirectToRoute('app_list_show', ['listId' => $listId]);
        }

        if (!$list->getAssignedUsers()->contains($user)) {
            $list->addAssignedUser($user);
            $this->entityManager->flush();
            $this->addFlash('success', 'User assigned to list!');
        } else {
            $this->addFlash('info', 'User already assigned to this list!');
        }

        return $this->redirectToRoute('app_list_show', ['listId' => $listId]);
    }

    #[Route('/{listId}/unassign-user/{userId}', name: 'app_list_unassign_user', methods: ['POST'])]
    public function unassignUserFromList(int $listId, int $userId): Response
    {
        $list = $this->getListById($listId);
        $user = $this->entityManager->getRepository(\App\Entity\User::class)->find($userId);

        if (!$user) {
            $this->addFlash('error', 'User not found!');
            return $this->redirectToRoute('app_list_show', ['listId' => $listId]);
        }

        $list->removeAssignedUser($user);
        $this->entityManager->flush();

        $this->addFlash('success', 'User unassigned from list!');
        return $this->redirectToRoute('app_list_show', ['listId' => $listId]);
    }

    #[Route('/{listId}/item/{itemId}/assign-user', name: 'app_list_item_assign_user', methods: ['POST'])]
    public function assignUserToItem(Request $request, int $listId, int $itemId): Response
    {
        $list = $this->getListById($listId);
        $item = $this->getItemById($itemId);

        if ($item->getItemList()->getId() !== $list->getId()) {
            throw new NotFoundHttpException('Item does not belong to this list');
        }

        $userId = (int) $request->request->get('userId');
        $user = $this->entityManager->getRepository(\App\Entity\User::class)->find($userId);

        if (!$user) {
            $this->addFlash('error', 'User not found!');
            return $this->redirectToRoute('app_list_show', ['listId' => $listId]);
        }

        if (!$item->getAssignedUsers()->contains($user)) {
            $item->addAssignedUser($user);
            $this->entityManager->flush();
            $this->addFlash('success', 'User assigned to item!');
        } else {
            $this->addFlash('info', 'User already assigned to this item!');
        }

        return $this->redirectToRoute('app_list_show', ['listId' => $listId]);
    }

    #[Route('/{listId}/item/{itemId}/unassign-user/{userId}', name: 'app_list_item_unassign_user', methods: ['POST'])]
    public function unassignUserFromItem(int $listId, int $itemId, int $userId): Response
    {
        $list = $this->getListById($listId);
        $item = $this->getItemById($itemId);

        if ($item->getItemList()->getId() !== $list->getId()) {
            throw new NotFoundHttpException('Item does not belong to this list');
        }

        $user = $this->entityManager->getRepository(\App\Entity\User::class)->find($userId);
        if (!$user) {
            $this->addFlash('error', 'User not found!');
            return $this->redirectToRoute('app_list_show', ['listId' => $listId]);
        }

        $item->removeAssignedUser($user);
        $this->entityManager->flush();

        $this->addFlash('success', 'User unassigned from item!');
        return $this->redirectToRoute('app_list_show', ['listId' => $listId]);
    }

    private function getListById(int $listId): ItemList
    {
        $list = $this->entityManager->getRepository(ItemList::class)->find($listId);
        if (!$list) {
            throw new NotFoundHttpException('List not found');
        }
        return $list;
    }

    private function getItemById(int $itemId): Item
    {
        $item = $this->entityManager->getRepository(Item::class)->find($itemId);
        if (!$item) {
            throw new NotFoundHttpException('Item not found');
        }
        return $item;
    }
}

