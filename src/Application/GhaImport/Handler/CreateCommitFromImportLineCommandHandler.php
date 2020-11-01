<?php

declare(strict_types=1);

namespace App\Application\GhaImport\Handler;

use App\Application\GhaImport\Command\CreateCommitFromImportLineCommand;
use App\Domain\GhaImport\Commit;
use App\Domain\GhaImport\CommitRepositoryInterface;

final class CreateCommitFromImportLineCommandHandler
{
    /** @var CommitRepositoryInterface */
    private $commitRepository;

    public function __construct(CommitRepositoryInterface $commitRepository)
    {
        $this->commitRepository = $commitRepository;
    }

    public function __invoke(CreateCommitFromImportLineCommand $command)
    {
        $commit = new Commit(
            $command->createdAt,
            $command->message,
            $command->commitId
        );

        $this->commitRepository->add($commit);

        return $commit;
    }
}
