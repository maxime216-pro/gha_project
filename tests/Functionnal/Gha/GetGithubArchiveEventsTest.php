<?php

namespace App\Tests\Functionnal\Gha;

use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class GetGithubArchiveEventsTest extends WebTestCase
{
    /** @var Client */
    protected $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        //Fixtures loading
        $loader = static::bootKernel()->getContainer()->get('fidry_alice_data_fixtures.loader.doctrine');
        $loader->load([
            __DIR__.'/../../fixtures/CommentFixtures.php',
            __DIR__.'/../../fixtures/PullRequestEventFixtures.php',
            __DIR__.'/../../fixtures/PushEventFixtures.php',
            __DIR__.'/../../fixtures/CommitFixtures.php',
        ]);
    }

    public function test_request_works_with_valid_filters()
    {
        $this->client->request(
            'POST',
            'https://127.0.0.1:8000/api/github-events', 
            [], 
            [], 
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode([
                'eventDate' => (new DateTimeImmutable('2020-01-01'))->format('Y-m-d'),
                'keyword' => 'group1'
            ]),
        );

        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(5, (json_decode($response->getContent())->comments));
        $this->assertCount(2, (json_decode($response->getContent())->pushs));
        $this->assertCount(5, (json_decode($response->getContent())->pull_requests));
        $this->assertEquals(3, (json_decode($response->getContent())->number_of_commits));
    }

    public function test_that_empty_or_invalid_parameters_failed()
    {
        $this->client->request(
            'POST',
            'https://127.0.0.1:8000/api/github-events', 
            [], 
            [], 
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode([
                'eventDate' => (new DateTimeImmutable('2020-01-01'))->format('Y-m-d'),
            ]),
        );

        $response = $this->client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(
            '<!-- The content passed to the request is invalidThe required option &quot;keyword&quot; is missing. (400 Bad Request) -->',
            substr($response->getContent(), 0, 122)
        );

        $this->client->request(
            'POST',
            'https://127.0.0.1:8000/api/github-events', 
            [], 
            [], 
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode([
                'eventDate' => 235,
                'keyword' => 'group1'
            ]),
        );

        $response = $this->client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(
            '<!-- The content passed to the request is invalidThe option &quot;eventDate&quot; with value 235 is expected to be of type &quot;string&quot;, but is of type &quot;integer&quot;. (400 Bad Request) -->',
            substr($response->getContent(), 0, 200)
        );
    }

    public function test_request_works_with_valid_filters_and_any_corresponding_event()
    {
        $this->client->request(
            'POST',
            'https://127.0.0.1:8000/api/github-events', 
            [], 
            [], 
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode([
                'eventDate' => (new DateTimeImmutable('2014-01-01'))->format('Y-m-d'),
                'keyword' => '\452#$@'
            ]),
        );

        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(0, (json_decode($response->getContent())->comments));
        $this->assertCount(0, (json_decode($response->getContent())->pushs));
        $this->assertCount(0, (json_decode($response->getContent())->pull_requests));
        $this->assertEquals(0, (json_decode($response->getContent())->number_of_commits));
    }
}
