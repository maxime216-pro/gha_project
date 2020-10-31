<?php

declare(strict_types=1);

namespace App\Application\GhaImport\Command;

use DateTimeInterface;

final class CreateCommitCommentFromImportLine
{
    /** @var DateTimeInterface */
    public $createdAt;

    /** @var string */
    public $commitComment;

    public function __construct(
        DateTimeInterface $createdAt,
        string $commitComment
    ) {
        $this->createdAt = $createdAt;
        $this->commitComment = $commitComment;
    }
}