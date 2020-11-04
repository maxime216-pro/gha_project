<?php

declare(strict_types=1);

namespace App\Domain\GhaImport;

interface CommitRepositoryInterface
{
    public function add(Commit $commit): void;
}
