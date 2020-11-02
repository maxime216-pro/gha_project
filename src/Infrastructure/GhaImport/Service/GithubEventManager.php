<?php

declare(strict_types=1);

namespace App\Infrastructure\GhaImport\Service;

use App\Application\GhaImport\Command\CommitDto;
use App\Application\GhaImport\Command\CreateCommentFromImportLineCommand;
use App\Application\GhaImport\Command\CreatePullRequestFromImportLineCommand;
use App\Application\GhaImport\Command\CreatePushFromImportLineCommand;
use App\Domain\GhaImport\Importable;
use App\Domain\GhaImport\Service\GithubEventManagerInterface;
use DateTime;

final class GithubEventManager implements GithubEventManagerInterface
{
    private const PULL_REQUEST = 'PullRequestEvent';
    private const PUSH = 'PushEvent';
    private const COMMENT = [
        'CommitCommentEvent',
        'IssueCommentEvent',
        'PullRequestReviewCommentEvent'
    ];

    public function getEventFromImport(object $line): ?Importable
    {
        if (self::PUSH === $line->type) {
            return new CreatePushFromImportLineCommand(
                new DateTime($line->created_at),
                $line->repo->name,
                $this->handleCommits($line->payload->commits)
            );
        } elseif (self::PULL_REQUEST === $line->type) {
            return new CreatePullRequestFromImportLineCommand(
                new DateTime($line->created_at),
                $line->repo->name,
                property_exists($line->payload->pull_request, 'body') ? $line->payload->pull_request->body : null,
                $line->payload->pull_request->commits,
                $line->payload->pull_request->comments
            );
        } elseif (\in_array($line->type, self::COMMENT)) {
            return new CreateCommentFromImportLineCommand(
                new DateTime($line->payload->comment->created_at),
                $line->repo->name,
                $line->payload->comment->body,
                $line->payload->comment->commit_id,
            );
        }

        return null;
    }

    /**
     * A push must contains at least one commit..
     */
    private function handleCommits(array $commits) {
        return array_map(function($commit) {
            return new CommitDto(
                $commit->message,
                $commit->sha
            );
        }, $commits);
    }
}
