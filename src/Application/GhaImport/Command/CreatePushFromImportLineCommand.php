<?php

declare(strict_types=1);

namespace App\Application\GhaImport\Command;

use App\Domain\GhaImport\Importable;
use DateTimeInterface;

final class CreatePushFromImportLineCommand  implements Importable
{
    /** @var DateTimeInterface */
    public $createdAt;

    /** @var string */
    public $repoName;

    /** @var array */
    public $commits;

    public function __construct(
        \DateTimeInterface $createdAt,
        string $repoName,
        array $commits
    ) {
        $this->createdAt = $createdAt;
        $this->repoName = $repoName;
        $this->commits = $commits;
    }
}
