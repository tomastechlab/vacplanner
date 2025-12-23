<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $itemName = null;

    #[ORM\ManyToOne(inversedBy: 'items')]
    private ?ItemList $itemList = null;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\ManyToMany(targetEntity: Comment::class)]
    private Collection $comments;

<<<<<<< HEAD
    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'items')]
    private Collection $assignedUser;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->assignedUser = new ArrayCollection();
=======
    public function __construct()
    {
        $this->comments = new ArrayCollection();
>>>>>>> ae5afe9df6bba8cff89586d0da02ca0c97c284e6
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getItemName(): ?string
    {
        return $this->itemName;
    }

    public function setItemName(string $itemName): static
    {
        $this->itemName = $itemName;

        return $this;
    }

    public function getItemList(): ?ItemList
    {
        return $this->itemList;
    }

    public function setItemList(?ItemList $itemList): static
    {
        $this->itemList = $itemList;

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
<<<<<<< HEAD

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
=======
>>>>>>> ae5afe9df6bba8cff89586d0da02ca0c97c284e6
}
