<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository\GhaImport;

use App\Domain\GhaImport\PullRequestEvent;
use App\Domain\GhaImport\PullRequestRepositoryInterface;
use App\Infrastructure\Doctrine\Repository\AbstractDoctrineRepository;
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

    public function findByDateAndKeyword(\DateTimeInterface $dateFilter, string $keyword): array
    {
        return $this
            ->entityManager
            ->createQueryBuilder()
            ->select('pr.createdAt', 'pr.repoName', 'pr.message', 'pr.numberOfCommits', 'pr.numberOfComments')
            ->from(PullRequestEvent::class, 'pr')
            ->where('pr.createdAt >= :dateFilter')
            ->andWhere('pr.createdAt < :upperDateFilter')
            ->andWhere('pr.message LIKE :keywordFilter')
            ->setParameter('dateFilter', $dateFilter->format('Y-m-d'))
            ->setParameter('upperDateFilter', date_modify($dateFilter, '+1 day')->format('Y-m-d'))
            ->setParameter('keywordFilter', '%'.$keyword.'%')
            ->orderBy('pr.createdAt')
            ->getQuery()
            ->getResult();
    }
}
