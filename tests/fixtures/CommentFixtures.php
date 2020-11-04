<?php

use App\Domain\GhaImport\Comment;

return [
    Comment::class => [
        'comment{1..5}' => [
            '__construct' => [
                'createdAt' => new DateTimeImmutable('2020-01-01'),
                'repoName' => '<text(50)>',
                'message' => 'group1 <realText(500, 2)>',
                'commitId' => '<uuid()>'
            ],
        ],
        'comment{6..12}' => [
            '__construct' => [
                'createdAt' => new DateTimeImmutable('2019-02-02'),
                'repoName' => '<text(50)>',
                'message' => 'group1 <realText(500, 2)>',
                'commitId' => '<uuid()>'
            ],
        ],
    ],
];
