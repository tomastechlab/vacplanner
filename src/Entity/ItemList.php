<?php

namespace App\Entity;

use App\Repository\ItemListRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemListRepository::class)]
class ItemList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $listName = null;

    #[ORM\Column(length: 255)]
    private ?string $relatedTo = null;

    #[ORM\Column]
    private ?int $relatedToId = null;

    /**
     * @var Collection<int, Item>
     */
    #[ORM\OneToMany(targetEntity: Item::class, mappedBy: 'itemList')]
    private Collection $items;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\ManyToMany(targetEntity: Comment::class)]
    private Collection $comments;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'itemLists')]
    private Collection $assignedUser;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->assignedUser = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getListName(): ?string
    {
        return $this->listName;
    }

    public function setListName(string $listName): static
    {
        $this->listName = $listName;

        return $this;
    }

    public function getRelatedTo(): ?string
    {
        return $this->relatedTo;
    }

    public function setRelatedTo(string $relatedTo): static
    {
        $this->relatedTo = $relatedTo;

        return $this;
    }

    public function getRelatedToId(): ?int
    {
        return $this->relatedToId;
    }

    public function setRelatedToId(int $relatedToId): static
    {
        $this->relatedToId = $relatedToId;

        return $this;
    }

    /**
     * @return Collection<int, ItemListItem>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setItemList($this);
        }

        return $this;
    }

    public function removeItem(Item $item): static
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getItemList() === $this) {
                $item->setItemList(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        $this->comments->removeElement($comment);

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getAssignedUsers(): Collection
    {
        return $this->assignedUser;
    }

    public function addAssignedUser(User $assignedUser): static
    {
        if (!$this->assignedUser->contains($assignedUser)) {
            $this->assignedUser->add($assignedUser);
        }

        return $this;
    }

    public function removeAssignedUser(User $assignedUser): static
    {
        $this->assignedUser->removeElement($assignedUser);

        return $this;
    }
}
