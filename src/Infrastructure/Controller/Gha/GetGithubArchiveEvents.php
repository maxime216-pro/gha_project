<?php

declare(strict_types = 1);

namespace App\Infrastructure\Controller\Gha;

use App\Application\GhaImport\Query\DateAndKeywordFilterQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\OptionsResolver\Exception\ExceptionInterface as OptionsResolverExceptionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class GetGithubArchiveEvents extends AbstractController
{
    /** @var array */
    private $payload = [];

    /** @var MessageBusInterface */
    private $queryBus;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function __invoke(Request $request)
    {
        $payload = $this->getPayload($request);
        $dateAndKeywordFilterQuery = new DateAndKeywordFilterQuery($payload['eventDate'], $payload['keyword']);
        $viewModel = $this->queryBus->dispatch($dateAndKeywordFilterQuery);

        return new JsonResponse($viewModel);
    }

    private function getPayload(Request $request): array
    {
        $content = $request->getContent();

        if (empty($content)) {
            return [];
        }

        $payload = json_decode($content, true);
        if (\is_null($payload)) {
            throw new BadRequestHttpException('The content passed to the request contains invalid JSON.');
        }

        $resolver = new OptionsResolver();
        $this->configurePayload($resolver);

        try {
            $resolver->resolve($payload);
        } catch (OptionsResolverExceptionInterface $e) {
            throw new BadRequestHttpException('The content passed to the request is invalid'.$e->getMessage());
        }

        return $payload;
    }

    private function configurePayload(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired('eventDate')->setAllowedTypes('event_date', 'string')
            ->setRequired('keyword')->setAllowedTypes('keyword', 'string')
        ;
    }
}
