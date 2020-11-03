<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository\GhaImport;

use App\Domain\GhaImport\Comment;
use App\Domain\GhaImport\CommentRepositoryInterface;
use App\Infrastructure\Doctrine\Repository\AbstractDoctrineRepository;
use Doctrine\Common\Collections\Collection;
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

    public function findByDateAndKeyword(\DateTimeInterface $dateFilter, string $keyword): ?Collection
    {
        return $this
            ->entityManager
            ->createQueryBuilder()
            ->select('createdAt', 'repoName', 'message', 'commitId')
            ->from(Comment::class, 'comment')
            ->where('DATE_FORMAT(comment.createdAt, \'%Y-%m-%d\' = :dateFilter')
            ->andWhere('comment.message LIKE :keywordFilter')
            ->setParameter('dateFilter', $dateFilter->format('Y-m-d'))
            ->setParameter('keyword', $keyword)
            ->orderBy('comment.createdAt')
            ->getQuery()
            ->getResult();
    }
}
