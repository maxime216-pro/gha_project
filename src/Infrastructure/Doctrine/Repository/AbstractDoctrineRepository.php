<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractDoctrineRepository
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
}