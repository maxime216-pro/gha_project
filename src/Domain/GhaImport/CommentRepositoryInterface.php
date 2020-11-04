<?php

declare(strict_types=1);

namespace App\Domain\GhaImport;

interface CommentRepositoryInterface
{
    public function add(Comment $comment): void;

    public function findByDateAndKeyword(\DateTimeInterface $dateFilter, string $keyword): array;
}
