<?php

declare(strict_types=1);

namespace App\Application\GhaImport\Handler;

use App\Application\GhaImport\Command\CommitDto;
use App\Application\GhaImport\Command\CreatePushFromImportLineCommand;
use App\Domain\GhaImport\Commit;
use App\Domain\GhaImport\CommitRepositoryInterface;
use App\Domain\GhaImport\PushEvent;
use App\Domain\GhaImport\PushRepositoryInterface;

final class CreatePushFromImportLineCommandHandler
{
    /** @var PushRepositoryInterface */
    private $pushRepository;

    /** @var CommitRepositoryInterface */
    private $commitRepository;

    public function __construct(
        PushRepositoryInterface $pushRepository,
        CommitRepositoryInterface $commitRepository
    ) {
        $this->pushRepository = $pushRepository;
        $this->commitRepository = $commitRepository;
    }

    public function __invoke(CreatePushFromImportLineCommand $command)
    {
        $pushEvent = new PushEvent(
            $command->createdAt,
            $command->repoName,
        );

        if ($command->commits) {
            /** @var CommitDto $commitDto */
            foreach ($command->commits as $commitDto) {
                $commit = new Commit(
                    $commitDto->message,
                    $commitDto->commitId
                );
                $pushEvent->addCommit($commit);
                $this->commitRepository->add($commit);
            }
        }

        $this->pushRepository->add($commit);

        return $commit;
    }
}
