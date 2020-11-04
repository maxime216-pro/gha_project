<?php

use App\Domain\GhaImport\PullRequestEvent;

return [
    PullRequestEvent::class => [
        'pr{1..5}' => [
            '__construct' => [
                'createdAt' => new DateTimeImmutable('2020-01-01'),
                'repoName' => '<text(50)>',
                'message' => 'group1 <realText(500, 2)>',
                'numberOfCommits' => '<numberBetween(0, 30)>',
                'numberOfComments' => '<numberBetween(0, 30)>'
            ],
        ],
        'pr{6..12}' => [
            '__construct' => [
                'createdAt' => new DateTimeImmutable('2019-02-02'),
                'repoName' => '<text(50)>',
                'message' => 'group2 <realText(500, 2)>',
                'numberOfCommits' => '<numberBetween(0, 30)>',
                'numberOfComments' => '<numberBetween(0, 30)>'
            ],
        ],
    ],
];
