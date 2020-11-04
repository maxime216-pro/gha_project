<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository\GhaImport;

use App\Domain\GhaImport\Comment;
use App\Domain\GhaImport\CommentRepositoryInterface;
use App\Infrastructure\Doctrine\Repository\AbstractDoctrineRepository;
use Doctrine\ORM\EntityManagerInterface;

final class CommentRepository extends AbstractDoctrineRepository implements CommentRepositoryInterface
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
    }

    public function add(Comment $comment): void
    {
        $this->entityManager->persist($comment);
    }

    public function findByDateAndKeyword(\DateTimeInterface $dateFilter, string $keyword): array
    {
        return $this
            ->entityManager
            ->createQueryBuilder()
            ->select('c.createdAt', 'c.repoName', 'c.message', 'c.commitId')
            ->from(Comment::class, 'c')
            ->where('c.createdAt >= :dateFilter')
            ->andWhere('c.createdAt < :upperDateFilter')
            ->andWhere('c.message LIKE :keywordFilter')
            ->setParameter('dateFilter', $dateFilter->format('Y-m-d'))
            ->setParameter('upperDateFilter', date_modify($dateFilter, '+1 day')->format('Y-m-d'))
            ->setParameter('keywordFilter', '%'.$keyword.'%')
            ->orderBy('c.createdAt')
            ->getQuery()
            ->getResult();
    }
}
