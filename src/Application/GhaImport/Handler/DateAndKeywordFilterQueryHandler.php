<?php

declare(strict_types=1);

namespace App\Application\GhaImport\Handler;

use App\Application\GhaImport\Query\DateAndKeywordFilterQuery;
use App\Domain\GhaImport\CommentRepositoryInterface;
use App\Domain\GhaImport\PullRequestRepositoryInterface;
use App\Domain\GhaImport\PushRepositoryInterface;
use DateTimeInterface;
use Doctrine\Common\Collections\Collection;

final class DateAndKeywordFilterQueryHandler
{
    /** @var CommentRepositoryInterface */
    private $commentRepo;

    /** @var PullRequestRepositoryInterface */
    private $pullRequestRepo;

    /** @var PushRepositoryInterface */
    private $pushRepo;

    public function __construct(
        CommentRepositoryInterface $commentRepo,
        PullRequestRepositoryInterface $pullRequestRepo,
        PushRepositoryInterface $pushRepo
    ) {
        $this->commentRepo = $commentRepo;
        $this->pullRequestRepo = $pullRequestRepo;
        $this->pushRepo = $pushRepo;
    }

    public function __invoke(DateAndKeywordFilterQuery $dateAndKeywordFilter): array
    {
        $comments = $this->commentRepo->findByDateAndKeyword($dateAndKeywordFilter->dateFilter, $dateAndKeywordFilter->keywordFilter);
        $pullRequest = $this->pullRequestRepo->findByDateAndKeyword($dateAndKeywordFilter->dateFilter, $dateAndKeywordFilter->keywordFilter);
        $pushs = $this->pushRepo->findByDateAndKeyword($dateAndKeywordFilter->dateFilter, $dateAndKeywordFilter->keywordFilter);

        return $this->presentResult(
            $comments,
            $pullRequest,
            $pushs,
            $dateAndKeywordFilter->dateFilter,
            $dateAndKeywordFilter->keywordFilter
        );
    }

    public function presentResult(
        Collection $comments,
        Collection $pullRequest,
        Collection $pushs,
        DateTimeInterface $dateFilter,
        string $keyword
    ): array
    {
        return [
            'comments' => $comments,
            'pull_requests' => $pullRequest,
            'pushs' => $pushs,
            'number_of_commits' => array_reduce($pushs->toArray(), function($curr, $push) {
                $curr += \count($push->getCommit());
            }, 0),
            'number_of_pull_requests' => \count($pullRequest),
            'number_of_comments' => \count($comments),
            'date_filter' => $dateFilter->format('Y-m-d'),
            'keyword' => $keyword
        ];
    }
}