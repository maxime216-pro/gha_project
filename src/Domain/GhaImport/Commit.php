<?php

declare(strict_types = 1);

namespace App\Domain\GhaImport;

use DateTimeInterface;

final class Commit
{
    /** @var int */
    private $id;

    /** @var \DateTimeInterface */
    private $createdAt;

    /** @var string */
    private $message;

    /** @var string */
    private $commitId;

    /** @var PushEvent */
    private $pushEvent;

    final public function __construct(
        \DateTimeInterface $createdAt,
        string $message,
        string $commitId
    ) {
        $this->createdAt = $createdAt;
        $this->message = $message;
        $this->commitId = $commitId;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
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
