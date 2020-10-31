<?php

declare(strict_types=1);

namespace App\Domain\GhaImport;

use Doctrine\Common\Collections\Collection;

interface ImportRepositoryInterface
{
    public function add(CommitComment $import);

    public function findCommitCommentsByDateAndKeyword(\DateTimeInterface $dateFilter, string $keyword): ?Collection;
}