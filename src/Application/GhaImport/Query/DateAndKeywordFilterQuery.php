<?php

declare(strict_types=1);

namespace App\Application\GhaImport\Query;

use DateTime;
use DateTimeInterface;

final class DateAndKeywordFilterQuery
{
    /** @var string */
    public $keywordFilter;

    /** @var DateTimeInterface */
    public $dateFilter;

    public function __construct(
        string $dateFilter,
        string $keywordFilter
    ) {
      $this->dateFilter = new DateTime($dateFilter);
      $this->keywordFilter = $keywordFilter; 
    }
}
