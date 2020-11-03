<?php

namespace App\Tests\Infrastructure\GhaImport\Service;

use App\Application\GhaImport\Command\CreateCommentFromImportLineCommand;
use App\Application\GhaImport\Command\CreatePullRequestFromImportLineCommand;
use App\Application\GhaImport\Command\CreatePushFromImportLineCommand;
use App\Infrastructure\GhaImport\Service\GithubEventManager;
use PHPUnit\Framework\TestCase;

class GithubEventManagerTest extends TestCase
{
    public function testGetEventFromImport()
    {
        $ghManager = new GithubEventManager();

        //
        // Handle Push Event
        //
        $commitDtoOne = ((object) [
            'message' => 'this is a nice message number 1',
            'sha' => 'ABCD1234',
            'distinct' => true,
        ]);

        $commitDtoTwo = ((object) [
            'message' => '我喜欢寿司',
            'sha' => 'EFGH5678',
            'distinct' => true,
        ]);
        $notDistinctCommit = ((object) [
            'message' => 'Im not an unique commit !',
            'sha' => 'EFGH9678',
            'distinct' => false,
        ]);
        $pushEventLine = ((object) [
            'type' => 'PushEvent',
            'created_at' => '2015-01-01T12:00:01Z',
            'distinct' => true,
            'repo' => ((object) ['name' => 'push repo']),
            'payload' => ((object) ['commits' => [$commitDtoOne, $commitDtoTwo, $notDistinctCommit]]),
        ]);
        $pushEventAsCommand = $ghManager->getEventFromImport($pushEventLine);

        $this->assertInstanceOf(CreatePushFromImportLineCommand::class, $pushEventAsCommand);
        $this->assertCount(2, $pushEventAsCommand->commits);

        //
        // Handle PullRequest Event
        //
        $pullRequestEventLine = ((object) [
            'type' => 'PullRequestEvent',
            'created_at' => '2020-01-01T12:00:01Z',
            'distinct' => true,
            'repo' => ((object) ['name' => 'pull request repo']),
            'payload' => ((object) [
                'pull_request' => (object)[
                    'body' => 'a awesome Body full of content',
                    'commits' => 5,
                    'comments' => 2
                ],
            ])
        ]);
        $pullRequestEventAsCommand = $ghManager->getEventFromImport($pullRequestEventLine);
        $this->assertInstanceOf(CreatePullRequestFromImportLineCommand::class, $pullRequestEventAsCommand);

        //
        // Handle Comment
        //
        $commentLine = ((object) [
            'type' => 'CommitCommentEvent',
            'repo' => ((object) ['name' => 'pull request repo']),
            'payload' => ((object) [
                'comment' => (object)[
                    'body' => 'A nice comment this time :)',
                    'commit_id' => 'zxcv9876',
                    'created_at' => '2018-01-01T12:00:01Z',
                ],
            ])
        ]);
        $commentLineAsCommand = $ghManager->getEventFromImport($commentLine);
        $this->assertInstanceOf(CreateCommentFromImportLineCommand::class, $commentLineAsCommand);

        //
        // Handle other event type
        //
        $nullEventLine = ((object) [
            'type' => 'ForkEvent',
        ]);
        $nullEventLineAsCommand = $ghManager->getEventFromImport($nullEventLine);
        $this->assertNull($nullEventLineAsCommand);
    }
}
