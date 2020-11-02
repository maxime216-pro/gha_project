<?php

declare(strict_types=1);

namespace App\Application\GhaImport\Command;

use App\Domain\GhaImport\Importable;
use DateTimeInterface;

final class CreateCommitFromImportLineCommand implements Importable
{
    /** @var DateTimeInterface */
    public $createdAt;

    /** @var string */
    public $message;

    /** @var string */
    public $commitId;

    public function __construct(
        DateTimeInterface $createdAt,
        string $message,
        string $commitId
    ) {
        $this->createdAt = $createdAt;
        $this->message = $message;
        $this->commitId= $commitId;
    }
}
