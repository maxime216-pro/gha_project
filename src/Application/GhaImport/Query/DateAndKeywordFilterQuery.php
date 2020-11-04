<?php

declare(strict_types=1);

namespace App\Application\GhaImport\Query;

final class DateAndKeywordFilterQuery
{
    /** @var string */
    public $keywordFilter;

    /** @var string */
    public $dateFilter;

    public function __construct(
        string $dateFilter,
        string $keywordFilter
    ) {
      $this->dateFilter = $dateFilter;
      $this->keywordFilter = $keywordFilter; 
    }
}
