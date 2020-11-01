<?php

declare(strict_types=1);

namespace App\Application\GhaImport\Handler;

use App\Application\GhaImport\Command\CreateCommitCommentFromImportLineCommand;
use App\Domain\GhaImport\CommitComment;
use App\Domain\GhaImport\CommitCommentRepositoryInterface;

final class CreateCommitCommentFromImportLineCommandHandler
{
    /** @var CommitCommentRepositoryInterface */
    private $commitCommentRespository;

    public function __construct(CommitCommentRepositoryInterface $commitCommentRespository)
    {
        $this->commitCommentRespository = $commitCommentRespository;
    }

    public function __invoke(CreateCommitCommentFromImportLineCommand $command)
    {
        $commitComment = new CommitComment(
            $command->createdAt,
            $command->commitComment
        );

        $this->commitCommentRespository->add($commitComment);

        return $commitComment;
    }
}