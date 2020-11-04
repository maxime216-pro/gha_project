<?php

declare(strict_types=1);

namespace App\Application\GhaImport\Handler;

use App\Application\GhaImport\Query\DateAndKeywordFilterQuery;
use App\Domain\GhaImport\CommentRepositoryInterface;
use App\Domain\GhaImport\PullRequestRepositoryInterface;
use App\Domain\GhaImport\PushRepositoryInterface;
use DateTime;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

final class DateAndKeywordFilterQueryHandler
{
    /** @var CommentRepositoryInterface */
    private $commentRepo;

    /** @var PullRequestRepositoryInterface */
    private $pullRequestRepo;

    /** @var PushRepositoryInterface */
    private $pushRepo;

    /** @var SerializerInterface */
    private $serializer;

    public function __construct(
        CommentRepositoryInterface $commentRepo,
        PullRequestRepositoryInterface $pullRequestRepo,
        PushRepositoryInterface $pushRepo,
        SerializerInterface $serializer
    ) {
        $this->commentRepo = $commentRepo;
        $this->pullRequestRepo = $pullRequestRepo;
        $this->pushRepo = $pushRepo;
        $this->serializer = $serializer;
    }

    public function __invoke(DateAndKeywordFilterQuery $dateAndKeywordFilter): array
    {
        $comments = $this->commentRepo->findByDateAndKeyword(new DateTime($dateAndKeywordFilter->dateFilter), $dateAndKeywordFilter->keywordFilter);
        $pullRequest = $this->pullRequestRepo->findByDateAndKeyword(new DateTime($dateAndKeywordFilter->dateFilter), $dateAndKeywordFilter->keywordFilter);
        $pushs = $this->pushRepo->findByDateAndKeyword(new DateTime($dateAndKeywordFilter->dateFilter), $dateAndKeywordFilter->keywordFilter);
        return $this->presentResult(
            $comments,
            $pullRequest,
            $pushs,
            $dateAndKeywordFilter->dateFilter,
            $dateAndKeywordFilter->keywordFilter
        );
    }

    public function presentResult(
        array $comments,
        array $pullRequest,
        array $pushs,
        string $dateFilter,
        string $keyword
    ): array
    {
        $encoder = [new JsonEncoder()];
        $normalizer = [
            new DateTimeNormalizer(),
            new ObjectNormalizer(null, null, null, null, null, null, [
                AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                    return [
                        $object->getCreatedAt(),
                        $object->getCommits()
                    ];
                },
            ]),
            new DateTimeNormalizer(),
        ];
        $serializer = new Serializer($normalizer, $encoder);
        $pushes = json_decode($serializer->serialize($pushs, 'json'));
        return [
            'comments' => $comments,
            'pull_requests' => $pullRequest,
            'pushs' => $pushes,
            'number_of_commits' => array_reduce($pushes, function($carry, $push) {
                return $carry + \count($push->commits);
            }, 0),
            'number_of_pull_requests' => \count($pullRequest),
            'number_of_comments' => \count($comments),
            'date_filter' => (new DateTime($dateFilter))->format('Y-m-d'),
            'keyword' => $keyword,
        ];
    }
}
