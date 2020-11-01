<?php

declare(strict_types=1);

namespace App\Application\GhaImport\Handler;

use App\Application\GhaImport\Command\CreatePushFromImportLineCommand;

use App\Domain\GhaImport\PushEvent;
use App\Domain\GhaImport\PushRepositoryInterface;

final class CreatePushFromImportLineCommandHandler
{
    public function __construct(PushRepositoryInterface $pushRepository)
    {
        $this->pushRepository = $pushRepository;
    }

    public function __invoke(CreatePushFromImportLineCommand $command)
    {
        $pushEvent = new PushEvent(
            $command->createdAt,
            $command->repoName,
        );

        if ($command->commits) {
            foreach ($command->commits as $commit) {
                $pushEvent->addCommit($commit);
            }
        }

        $this->pushRepository->add($commit);

        return $commit;
    }
}
