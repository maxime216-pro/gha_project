<?php

declare(strict_types=1);

namespace App\Application\GhaImport\Handler;

use App\Application\GhaImport\Command\CreateCommentFromImportLineCommand;
use App\Domain\GhaImport\Comment;
use App\Domain\GhaImport\CommentRepositoryInterface;


final class CreateCommentFromImportLineCommandHandler
{
    /** @var CommentRepositoryInterface */
    private $commentRepository;

    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function __invoke(CreateCommentFromImportLineCommand $command)
    {
        $comment = new Comment(
            $command->createdAt,
            $command->repoName,
            $command->message,
            $command->commitId
        );

        $this->commentRepository->add($comment);

        return $comment;
    }
}
