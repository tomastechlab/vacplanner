<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Poll;
use App\Entity\PollOption;
use App\Enum\PollTypes;
use App\Enum\Relations;
use App\Repository\PollOptionRepository;
use App\Repository\PollRepository;
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
    ): Poll
    {
        $relation = Relations::tryFrom($relation->value);
        $poll = new Poll();
        $poll->setQuestion($question);
        $poll->setIsMultipleChoice(true);
        $poll->setIsAnonymous(false);
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

    public function getPollComments(int $pollId): array
    {
        return $this->commentService->getCommentsByRelation(Relations::POLL, $pollId);
    }

    public function finishPoll($pollId): PollOption
    {
        $poll = $this->getPollById($pollId);
        $poll->setFinished(true);
        $this->entityManagerInterface->persist($poll);
        return $this->getWinningVote($pollId);
    }

    public function getWinningVote(int $pollId): ?PollOption
    {
        $poll = $this->getPollById($pollId);
        if(!$poll->isFinished()) return null;
        return $this->findWinningOption($pollId);
    }

    private function findWinningOption(int $pollId): ?PollOption
    {
        return $this->entityManagerInterface
            ->createQueryBuilder('po')
            ->where('po.poll = :pollId')
            ->setParameter('pollId', $pollId)
            ->orderBy('po.votes', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
