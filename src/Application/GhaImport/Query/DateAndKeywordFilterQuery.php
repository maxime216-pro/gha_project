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
        string $keywordFilter,
        string $dateFilter
    ) {
      $this->keywordFilter = $keywordFilter;
      $this->dateFilter = new DateTime($dateFilter);  
    }
}
