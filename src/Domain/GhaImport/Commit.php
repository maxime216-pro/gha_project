<?php

declare(strict_types = 1);

namespace App\Domain\GhaImport;

final class Commit
{
    /** @var int */
    private $id;

    /** @var string */
    private $message;

    /** @var string */
    private $commitId;

    /** @var PushEvent */
    private $pushEvent;

    final public function __construct(
        string $message,
        string $commitId
    ) {
        $this->message = $message;
        $this->commitId = $commitId;
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
