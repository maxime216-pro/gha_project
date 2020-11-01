<?php

declare(strict_types=1);

namespace App\Domain\GhaImport;

use Doctrine\Common\Collections\Collection;

interface PullRequestRepositoryInterface
{
    public function add(PullRequestEvent $pullRequestEvent): void;

    public function findByDateAndKeyword(\DateTimeInterface $dateFilter, string $keyword): ?Collection;
}
