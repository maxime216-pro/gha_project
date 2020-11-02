<?php

declare(strict_types = 1);

namespace App\Domain\GhaImport;

use DateTimeInterface;

final class PullRequestEvent
{
    /** @var int */
    private $id;

    /** @var \DateTimeInterface */
    private $createdAt;

    /** @var string */
    private $repoName;

    /** @var string|null */
    private $message;

    /** @var int */
    private $numberOfCommits;

    /** @var int */
    private $numberOfComments;

    final public function __construct(
        \DateTimeInterface $createdAt,
        string $repoName,
        ?string $message,
        int $numberOfCommits,
        int $numberOfComments
    ) {
        $this->createdAt = $createdAt;
        $this->repoName = $repoName;
        $this->message = $message;
        $this->numberOfCommits = $numberOfCommits;
        $this->numberOfComments = $numberOfComments;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getRepoName(): string
    {
        return $this->repoName;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function getNumberOfCommits(): int
    {
        return $this->numberOfCommits;
    }

    public function getNumberOfComments(): int
    {
        return $this->numberOfComments;
    }
}
