<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\ItemList;
use App\Entity\Item;
use App\Entity\ItemListItem;
use App\Enum\Relations;
use App\Repository\ItemListRepository;
use Doctrine\ORM\EntityManagerInterface;

class ListService {

    public function __construct(
        private ItemListRepository $listRepository,
        private Item $itemListItemRepository,
        private ItemListItem $itemListItemRepository,
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

        $this->entityManagerInterface->persist($list);
        $this->entityManagerInterface->flush();
        $this->listRepository->persist($list);
        $this->listRepository->flush();

        return $list;
    }

    public function addItemToList(ItemList $list, string $itemName): Item
    {
        $item = new Item();
    public function addItemToList(ItemList $list, string $itemName): ItemListItem
    {
        $item = new ItemListItem();
        $item->setItemName($itemName);
        $item->setItemList($list);
        $this->entityManagerInterface->persist($item);
        $this->entityManagerInterface->flush();

        return $item;
    }
}
