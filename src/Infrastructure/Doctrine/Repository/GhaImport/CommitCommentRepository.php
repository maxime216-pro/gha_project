<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository\GhaImport;

use App\Domain\GhaImport\CommitComment;
use App\Domain\GhaImport\CommitCommentRepositoryInterface;
use App\Infrastructure\Doctrine\Repository\AbstractDoctrineRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

final class CommitCommentRepository extends AbstractDoctrineRepository implements CommitCommentRepositoryInterface
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
    }

    public function add(CommitComment $commitComment): void
    {
        $this->entityManager->persist($commitComment);
    }

    public function findCommitCommentsByDateAndKeyword(\DateTimeInterface $dateFilter, string $keyword): ?Collection
    {

    }
}