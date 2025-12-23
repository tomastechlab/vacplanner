<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\ItemList;
<<<<<<< HEAD
use App\Entity\Item;
=======
use App\Entity\ItemListItem;
>>>>>>> ae5afe9df6bba8cff89586d0da02ca0c97c284e6
use App\Enum\Relations;
use App\Repository\ItemListRepository;
use Doctrine\ORM\EntityManagerInterface;

class ListService {

    public function __construct(
        private ItemListRepository $listRepository,
<<<<<<< HEAD
        private Item $itemListItemRepository,
=======
        private ItemListItem $itemListItemRepository,
>>>>>>> ae5afe9df6bba8cff89586d0da02ca0c97c284e6
        private CommentService $commentService,
        private EntityManagerInterface $entityManagerInterface
    )
    {}

    public function createList(string $name, Relations $relatedTo = Relations::GENERAL, int $relatedToId = 0): ItemList
    {
        $list = new ItemList();
        $list->setListName($name);
        $list->setRelatedTo($relatedTo->value);
        $list->setRelatedToId($relatedToId);

<<<<<<< HEAD
        $this->entityManagerInterface->persist($list);
        $this->entityManagerInterface->flush();
=======
        $this->listRepository->persist($list);
        $this->listRepository->flush();
>>>>>>> ae5afe9df6bba8cff89586d0da02ca0c97c284e6

        return $list;
    }

<<<<<<< HEAD
    public function addItemToList(ItemList $list, string $itemName): Item
    {
        $item = new Item();
=======
    public function addItemToList(ItemList $list, string $itemName): ItemListItem
    {
        $item = new ItemListItem();
>>>>>>> ae5afe9df6bba8cff89586d0da02ca0c97c284e6
        $item->setItemName($itemName);
        $item->setItemList($list);
        $this->entityManagerInterface->persist($item);
        $this->entityManagerInterface->flush();

        return $item;
    }
}
