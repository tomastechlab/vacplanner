<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'comments')]
    private Collection $creator;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $comment = null;

    #[ORM\Column]
    private ?\DateTime $datetime = null;

    #[ORM\Column(length: 255)]
    private ?string $relatedTo = null;

    #[ORM\Column]
    private ?int $relatedToId = null;

    public function __construct()
    {
        $this->creator = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, User>
     */
    public function getCreator(): Collection
    {
        return $this->creator;
    }

    public function addCreator(User $creator): static
    {
        if (!$this->creator->contains($creator)) {
            $this->creator->add($creator);
        }

        return $this;
    }

    public function removeCreator(User $creator): static
    {
        $this->creator->removeElement($creator);

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getDatetime(): ?\DateTime
    {
        return $this->datetime;
    }

    public function setDatetime(\DateTime $datetime): static
    {
        $this->datetime = $datetime;

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
}
