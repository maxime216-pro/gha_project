<?php

declare(strict_types=1);

namespace App\Application\GhaImport\Dto;

final class CommitDto
{
    /** @var string */
    public $message;

    /** @var string */
    public $commitId;

    public function __construct(
        string $message,
        string $commitId
    ) {
        $this->message = $message;
        $this->commitId= $commitId;
    }
}
