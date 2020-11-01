<?php

declare(strict_types=1);

namespace App\Application\GhaImport\Handler;

use App\Application\GhaImport\Command\CreatePullRequestFromImportLineCommand;
use App\Domain\GhaImport\PullRequestEvent;
use App\Domain\GhaImport\PushRepositoryInterface;

final class CreatePullRequestFromImportLineCommandHandler
{
    public function __construct(PushRepositoryInterface $pullRequestRepository)
    {
        $this->pullRequestRepository = $pullRequestRepository;
    }

    public function __invoke(CreatePullRequestFromImportLineCommand $command)
    {
        $pullRequestEvent = new PullRequestEvent(
            $command->createdAt,
            $command->repoName,
            $command->message,
            $command->numberOfCommits,
            $command->numberOfComments
        );

        $this->pullRequestRepository->add($pullRequestEvent);

        return $pullRequestEvent;
    }
}
