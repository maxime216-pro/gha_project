<?php

declare(strict_types=1);

namespace App\Domain\GhaImport;

use Doctrine\Common\Collections\Collection;

interface CommitCommentRepositoryInterface
{
    public function add(CommitComment $commitComment): void;

    public function findCommitCommentsByDateAndKeyword(\DateTimeInterface $dateFilter, string $keyword): ?Collection;
}