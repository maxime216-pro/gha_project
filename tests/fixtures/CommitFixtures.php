<?php

use App\Domain\GhaImport\Commit;

return [
    Commit::class => [
        'commit1' => [
            '__construct' => [
                'message' => 'group1 <realText(500, 2)>',
                'commitId' => '<uuid()>',
                'pushEvent' => '@push1',
            ],
        ],
        'commit2' => [
            '__construct' => [
                'message' => 'group1 <realText(500, 2)>',
                'commitId' => '<uuid()>',
                'pushEvent' => '@push1',
            ],
        ],
        'commit3' => [
            '__construct' => [
                'message' => 'group1 <realText(500, 2)>',
                'commitId' => '<uuid()>',
                'pushEvent' => '@push2',
            ],
        ],
        'commit4' => [
            '__construct' => [
                'message' => 'group2 <realText(500, 2)>',
                'commitId' => '<uuid()>',
                'pushEvent' => '@push3',
            ],
        ],
        'commit5' => [
            '__construct' => [
                'message' => 'group2 <realText(500, 2)>',
                'commitId' => '<uuid()>',
                'pushEvent' => '@push4',
            ],
        ],
        'commit6' => [
            '__construct' => [
                'message' => 'group3 <realText(500, 2)>',
                'commitId' => '<uuid()>',
                'pushEvent' => '@push5',
            ],
        ],
        'commit7' => [
            '__construct' => [
                'message' => 'group2 <realText(500, 2)>',
                'commitId' => '<uuid()>',
                'pushEvent' => '@push5',
            ],
        ],
        'commit8' => [
            '__construct' => [
                'message' => 'group3 <realText(500, 2)>',
                'commitId' => '<uuid()>',
                'pushEvent' => '@push5',
            ],
        ],
    ],
];
