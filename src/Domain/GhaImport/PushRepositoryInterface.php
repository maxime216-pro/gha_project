<?php

declare(strict_types=1);

namespace App\Domain\GhaImport;

interface PushRepositoryInterface
{
    public function add(PushEvent $pushEvent): void;

    public function findByDateAndKeyword(\DateTimeInterface $dateFilter, string $keyword): array;
}
