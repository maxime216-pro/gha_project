<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository\GhaImport;

use App\Domain\GhaImport\PullRequestEvent;
use App\Domain\GhaImport\PullRequestRepositoryInterface;
use App\Infrastructure\Doctrine\Repository\AbstractDoctrineRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

final class PullRequestRepository extends AbstractDoctrineRepository implements PullRequestRepositoryInterface
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
    }

    public function add(PullRequestEvent $pullRequestEvent): void
    {
        $this->entityManager->persist($pullRequestEvent);
    }

    public function findByDateAndKeyword(\DateTimeInterface $dateFilter, string $keyword): ?Collection
    {
        return $this
            ->entityManager
            ->createQueryBuilder()
            ->select('repoName', 'message', 'numberOfCommits', 'numberOfComments')
            ->from(PullRequestEvent::class, 'pull_request')
            ->where('DATE_FORMAT(pull_request.createdAt, \'%Y-%m-%d\' = :dateFilter')
            ->andWhere('pull_request.message LIKE :keywordFilter')
            ->setParameter('dateFilter', $dateFilter->format('Y-m-d'))
            ->setParameter('keyword', $keyword)
            ->orderBy('pull_request.createdAt')
            ->getQuery()
            ->getResult();
    }
}
