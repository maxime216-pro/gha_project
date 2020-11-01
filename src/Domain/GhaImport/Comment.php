<?php

declare(strict_types = 1);

namespace App\Domain\GhaImport;

use DateTimeInterface;

final class Comment
{
    /** @var int */
    private $id;

    /** @var \DateTimeInterface */
    private $createdAt;

    /** @var string */
    private $repoName;

    /** @var string */
    private $message;

    /** @var string */
    private $commitId;

    final public function __construct(
        \DateTimeInterface $createdAt,
        string $repoName,
        string $message,
        string $commitId
    ) {
        $this->createdAt = $createdAt;
        $this->repoName = $repoName;
        $this->message = $message;
        $this->commitId = $commitId;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getRepoName(): string
    {
        return $this->repoName;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getCommitId(): string
    {
        return $this->commitId;
    }
}
