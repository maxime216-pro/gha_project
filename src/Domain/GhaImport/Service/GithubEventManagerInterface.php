<?php

declare(strict_types=1);

namespace App\Domain\GhaImport\Service;

use App\Domain\GhaImport\Importable;

interface GithubEventManagerInterface
{
    public function getEventFromImport(object $line): ?Importable;
}
