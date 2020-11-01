<?php

declare(strict_types=1);

namespace App\Application\GhaImport\Command;

use DateTimeInterface;

final class CreatePullRequestFromImportLineCommand
{
    /** @var DateTimeInterface */
    public $createdAt;

    /** @var string */
    public $repoName;

    /** @var string */
    public $message;

    /** @var int */
    public $numberOfCommits;

    /** @var int */
    public $numberOfComments;

    public function __construct(
        \DateTimeInterface $createdAt,
        string $repoName,
        string $message,
        int $numberOfCommits,
        int $numberOfComments
    ) {
        $this->createdAt = $createdAt;
        $this->repoName = $repoName;
        $this->message = $message;
        $this->numberOfCommits = $numberOfCommits;
        $this->numberOfComments = $numberOfComments;
    }
}
