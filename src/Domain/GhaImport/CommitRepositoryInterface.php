<?php

declare(strict_types=1);

namespace App\Domain\GhaImport;

use Doctrine\Common\Collections\Collection;

interface CommitRepositoryInterface
{
    public function add(Commit $commit): void;

    public function findByDateAndKeyword(\DateTimeInterface $dateFilter, string $keyword): ?Collection;
}
