<?php

namespace App\Entity;

use App\Repository\PollRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PollRepository::class)]
class Poll
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $question = null;

    #[ORM\Column(type: 'boolean')]
    private ?bool $isMultipleChoice = null;

    /**
     * @var Collection<int, PollOption>
     */
    #[ORM\OneToMany(targetEntity: PollOption::class, mappedBy: 'poll', orphanRemoval: true, cascade: ['persist'], fetch:'EAGER')]
    private Collection $options;

    #[ORM\Column]
    private bool $isAnonymous = false;

    #[ORM\Column(length: 255)]
    private ?string $relatedTo = null;

    #[ORM\Column]
    private ?int $relatedToId = null;

    #[ORM\Column(length: 255)]
    private ?string $pollType = null;

    #[ORM\Column]
    private ?bool $finished = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $votingStart = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $votingEnd = null;

    public function __construct()
    {
        $this->options = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): static
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @return Collection<int, PollOption>
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(PollOption $option): static
    {
        if (!$this->options->contains($option)) {
            $this->options->add($option);
            $option->setPoll($this);
        }

        return $this;
    }

    public function removeOption(PollOption $option): static
    {
        if ($this->options->removeElement($option)) {
            // set the owning side to null (unless already changed)
            if ($option->getPoll() === $this) {
                $option->setPoll(null);
            }
        }

        return $this;
    }

    public function isMultipleChoice(): ?bool
    {
        return $this->isMultipleChoice;
    }

    public function setIsMultipleChoice(bool $isMultipleChoice): static
    {
        $this->isMultipleChoice = $isMultipleChoice;

        return $this;
    }

    public function getVoteCount(): int
    {
        $voteCount = 0;
        foreach ($this->options as $option) {
            $voteCount += $option->getVotes();
        }
        return $voteCount;
    }

    public function isAnonymous(): ?bool
    {
        return $this->isAnonymous;
    }

    public function setIsAnonymous(bool $isAnonymous): static
    {
        $this->isAnonymous = $isAnonymous;

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

    public function getPollType(): ?string
    {
        return $this->pollType;
    }

    public function setPollType(string $pollType): static
    {
        $this->pollType = $pollType;

        return $this;
    }

    public function isFinished(): ?bool
    {
        return $this->finished;
    }

    public function setFinished(bool $finished): static
    {
        $this->finished = $finished;

        return $this;
    }

    public function getVotingStart(): ?\DateTime
    {
        return $this->votingStart;
    }

    public function setVotingStart(?\DateTime $votingStart): static
    {
        $this->votingStart = $votingStart;

        return $this;
    }

    public function getVotingEnd(): ?\DateTime
    {
        return $this->votingEnd;
    }

    public function setVotingEnd(?\DateTime $votingEnd): static
    {
        $this->votingEnd = $votingEnd;

        return $this;
    }
}
