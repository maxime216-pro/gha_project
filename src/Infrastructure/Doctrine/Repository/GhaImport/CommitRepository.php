<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository\GhaImport;

use App\Domain\GhaImport\Commit;
use App\Domain\GhaImport\CommitRepositoryInterface;
use App\Infrastructure\Doctrine\Repository\AbstractDoctrineRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

final class CommitRepository extends AbstractDoctrineRepository implements CommitRepositoryInterface
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
    }

    public function add(Commit $commit): void
    {
        $this->entityManager->persist($commit);
    }

    public function findByDateAndKeyword(\DateTimeInterface $dateFilter, string $keyword): ?Collection
    {

    }
}
