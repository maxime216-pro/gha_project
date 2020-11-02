<?php

namespace App\Tests\Infrastructure\GhaImport\Service;

use App\Application\GhaImport\Command\CreatePushFromImportLineCommand;
use App\Infrastructure\GhaImport\Service\GithubEventManager;
use DateTime;
use PHPUnit\Framework\TestCase;
use stdClass;

class GithubEventManagerTest extends TestCase
{
    public function testGetEventFromImport()
    {
        $ghManager = new GithubEventManager();

        // Handle Push Event
        $commitDtoOne = new stdClass();
        $commitDtoOne->message = 'this is a nice message number 1';
        $commitDtoOne->sha = 'ABCD1234';
        $commitDtoTwo = new stdClass();
        $commitDtoTwo->message = '我喜欢寿司';
        $commitDtoTwo->sha = 'EFGH5678';
        $pushEventLine = new stdClass();
        $pushEventLine->type = 'PushEvent';
        $pushEventLine->created_at = '2015-01-01T12:00:01Z';
        $pushEventLine->repo = ((object) ['name' => 'push repo']);
        $pushEventLine->payload = ((object) ['commits' => [$commitDtoOne, $commitDtoTwo]]);
        $pushEventAsCommand = $ghManager->getEventFromImport($pushEventLine);

        $this->assertInstanceOf(CreatePushFromImportLineCommand::class, $pushEventAsCommand);
        $this->assertCount(2, $pushEventAsCommand->commits);
    }
}
