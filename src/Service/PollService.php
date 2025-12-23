<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Poll;
use App\Entity\PollOption;
use App\Enum\PollTypes;
use App\Enum\Relations;
use App\Repository\PollOptionRepository;
use App\Repository\PollRepository;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\User\UserInterface;

class PollService {

    public function __construct(
        private PollRepository $pollRepository,
        private PollOptionRepository $pollOptionRepository,
        private EntityManagerInterface $entityManagerInterface,
        private CommentService $commentService
    )
    {}

    public function createPoll(
        string $question,
        array $pollOptions,
        Relations $relation = Relations::GENERAL,
        int $relationId = 0,
        PollTypes $pollType = PollTypes::GENERAL,
        ?DateTime $votingStart = null,
        ?DateTime $votingEnd = null
    ): Poll
    {
        $relation = Relations::tryFrom($relation->value);
        $poll = new Poll();
        $poll->setQuestion($question);
        $poll->setIsMultipleChoice(true);
        $poll->setIsAnonymous(false);
        $poll->setFinished(false);
        $poll->setVotingStart($votingStart);
        $poll->setVotingEnd($votingEnd);
        $poll->setRelatedTo($relation->value);
        $poll->setRelatedToId($relationId);
        $poll->setPollType($pollType->value);

        foreach ($pollOptions as $key => $option) {
            $optionName = $option;
            $option = new PollOption();
            $option->setName($optionName);
            $option->setVotes(0);
            $option->setPoll($poll);
            // $pollOptions[$key] = $option;
            $poll->addOption($option);
        }

        $this->entityManagerInterface->persist($poll);
        $this->entityManagerInterface->flush();

        return $poll;
    }

    public function addOption(int $pollId, string $optionName): ?PollOption
    {
        $poll = $this->getPollById($pollId);

        if ($poll->isFinished()) return null;

        $option = new PollOption();
        $option->setName($optionName);
        $option->setVotes(0);
        $option->setPoll($poll);

        $this->entityManagerInterface->persist($option);
        $this->entityManagerInterface->flush();

        return $option;
    }

    public function removeOption(int $optionId): void
    {
        $option = $this->pollOptionRepository->find($optionId);

        if (!$option) return;
        $poll = $option->getPoll();

        if ($poll->isFinished()) {
            throw new \Exception('Cannot remove option from finished poll');
        }

        if ($option->getVotes() > 0) {
            throw new \Exception('Cannot remove option that has votes');
        }

        $this->entityManagerInterface->remove($option);
        $this->entityManagerInterface->flush();
    }

    public function getPollByRelation(Relations $relatedTo, int $relatedToId, null|PollTypes $pollType = null):?Poll
    {
        $poll = $this->pollRepository->findOneBy([
            'relatedTo' => $relatedTo->value,
            'relatedToId' => $relatedToId,
            'pollType' => $pollType ? $pollType->value : null
        ]);
        if(!$poll) {
            return null;
        }
        return $poll;
    }

    public function getPollById(int $pollId): Poll
    {
        $poll = $this->pollRepository->find($pollId);
        if(!$poll) {
            throw new NotFoundHttpException('Poll not found');
        }
        return $poll;
    }

    public function getPollOptions(int $pollId): Collection
    {
        $poll = $this->getPollById($pollId);
        return $poll->getOptions();
    }

    public function vote(int $pollId, array $selectedOptions, UserInterface $user): void
    {
        $poll = $this->getPollById($pollId);
        if(!$this->isVotingOpen($pollId)) return;
        if(!$this->userCanVote($poll, $user)) return;
        $pollOptions = $poll->getOptions();

        if (!$poll->isMultipleChoice() && count($selectedOptions) > 1) {
            throw new \Exception('Only one option can be selected for this poll');
        }

        foreach ($pollOptions as $pollOption) {
            if (in_array($pollOption->getId(), $selectedOptions)) {
                $pollOption->setVotes($pollOption->getVotes() + 1);
                $pollOption->addUser($user);
        if(!$poll = $this->getPollById($pollId)) {
            throw new NotFoundHttpException('Poll not found');
        }
        $pollOptions = $poll->getOptions();

        foreach ($pollOptions as $pollOption) {
            $pollOption->setVotes(0);
            foreach ($selectedOptions as $selectedOptionId) {
                if ($pollOption->getId() == $selectedOptionId) {
                    $pollOption->setVotes($pollOption->getVotes() + 1);
                }
                if(!$poll->isAnonymous()) {
                    $pollOption->addUser($user);
                }
            }
        }

        $this->entityManagerInterface->flush();
    }

    private function userCanVote(Poll $poll, UserInterface $user): bool
    {
        if($poll->isFinished()) return false;

        $pollOptions = $poll->getOptions();
        foreach ($pollOptions as $pollOption) {
            if($pollOption->getUsers()->contains($user)) {
                return false;
            }
        }
        return true;
    }

    public function isVotingOpen(int $pollId): bool
    {
        $poll = $this->getPollById($pollId);
        if($poll->isFinished()) return false;

        $now = new \DateTime();
        if($poll->getVotingStart() && $now < $poll->getVotingStart()) {
            return false;
        }
        if($poll->getVotingEnd() && $now > $poll->getVotingEnd()) {
            return false;
        }
        return true;
    }

    public function getPollComments(int $pollId): array
    {
        return $this->commentService->getCommentsByRelation(Relations::POLL, $pollId);
    }

    public function finishPoll($pollId): PollOption
    {
        $poll = $this->getPollById($pollId);
        $poll->setFinished(true);
        $this->entityManagerInterface->persist($poll);
        $this->entityManagerInterface->flush();
        return $this->getWinningVote($pollId);
    }

    public function getWinningVote(int $pollId): ?PollOption
    {
        $poll = $this->getPollById($pollId);
        if(!$poll->isFinished()) return null;
        return $this->pollOptionRepository->findWinningOption($pollId);
    }

    public function editPollQuestion(int $pollId, string $newQuestion): void
    {
        $poll = $this->getPollById($pollId);
        if ($poll->isFinished()) return;

        $poll->setQuestion($newQuestion);
        $this->entityManagerInterface->flush();
    }

    public function setPollDates(int $pollId, ?\DateTime $startDate, ?\DateTime $endDate): void
    {
        $poll = $this->getPollById($pollId);

        if ($startDate && $endDate && $startDate > $endDate) {
            throw new \Exception('Startdate and Enddate given. Start date must be before end date');
        }

        $poll->setVotingStart($startDate);
        $poll->setVotingEnd($endDate);
        $this->entityManagerInterface->flush();
    }

    public function deletePoll(int $pollId): void
    {
        $poll = $this->getPollById($pollId);
        $this->entityManagerInterface->remove($poll);
        $this->entityManagerInterface->flush();
    }
}
