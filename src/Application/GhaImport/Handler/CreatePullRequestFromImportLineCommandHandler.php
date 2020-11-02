<?php

declare(strict_types=1);

namespace App\Application\GhaImport\Handler;

use App\Application\GhaImport\Command\CreatePullRequestFromImportLineCommand;
use App\Domain\GhaImport\PullRequestEvent;
use App\Infrastructure\Doctrine\Repository\GhaImport\PullRequestRepository;

final class CreatePullRequestFromImportLineCommandHandler
{
    public function __construct(PullRequestRepository $pullRequestRepository)
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
