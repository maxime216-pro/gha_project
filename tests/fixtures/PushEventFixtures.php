<?php

use App\Domain\GhaImport\PushEvent;

return [
    PushEvent::class => [
        'push{1..3}' => [
            '__construct' => [
                'createdAt' => new DateTimeImmutable('2020-01-01'),
                'repoName' => '<text(50)>',
            ],
        ],
        'push{4..5}' => [
            '__construct' => [
                'createdAt' => new DateTimeImmutable('2019-02-02'),
                'repoName' => '<text(50)>',
            ],
        ],
    ],
];
