<?php

declare(strict_types = 1);

namespace App\Domain\GhaImport;

use DateTimeInterface;

final class CommitComment
{
    /** @var int */
    private $id;

    /** @var \DateTimeInterface */
    private $createdAt;

    /** @var string */
    private $commitMessage;

    final public function __construct(
        \DateTimeInterface $createdAt,
        string $commitMessage
    ) {
        $this->createdAt = $createdAt;
        $this->commitMessage = $commitMessage;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getCommitMessage(): string
    {
        return $this->commitMessage;
    }
}