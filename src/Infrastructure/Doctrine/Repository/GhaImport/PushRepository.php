<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository\GhaImport;

use App\Domain\GhaImport\PushEvent;
use App\Domain\GhaImport\PushRepositoryInterface;
use App\Infrastructure\Doctrine\Repository\AbstractDoctrineRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

final class PushRepository extends AbstractDoctrineRepository implements PushRepositoryInterface
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
    }

    public function add(PushEvent $pushEvent): void
    {
        $this->entityManager->persist($pushEvent);
    }

    public function findByDateAndKeyword(\DateTimeInterface $dateFilter, string $keyword): ?Collection
    {

    }
}