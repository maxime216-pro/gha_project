<?php

declare(strict_types = 1);

namespace App\Infrastructure\Console\Command;

use App\Domain\GhaImport\Service\GithubEventManagerInterface;
use DateTime;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class ImportGhaDataCommand extends Command
{
    protected static $defaultName = 'app:import:gha';

    /** @var HttpClientInterface */
    private $client;

    /** @var MessageBusInterface */
    private $commandBus;

    /** @var GithubEventManagerInterface */
    private $ghEventManager;

    public function __construct(
        HttpClientInterface $client,
        MessageBusInterface $commandBus,
        GithubEventManagerInterface $ghEventManager
    ) {
        parent::__construct();
        $this->client = $client;
        $this->commandBus = $commandBus;
        $this->ghEventManager = $ghEventManager;
    }

    protected function configure()
    {
        $this->setDescription('Trigger an import of GitHub Archive')
            ->setHelp('This command allows you to import GitHub archive. You are able to set the desired start date. Import from beginning if any date is specified.')
            ->addOption('startDate', 's', InputOption::VALUE_OPTIONAL, "Import starting date (required format : 'Y-m-d H')");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $nowDate = new DateTime();
        if ($rawStartDate = $input->getOption('startDate')) {
            $currentParsingDate = DateTime::createFromFormat('Y-m-d H', $rawStartDate);
        } else {
            // GitHub started to archive events at this date
            $currentParsingDate = new DateTime('2020-10-01 00:00:00');
        }
        $output->writeln('start date is : ' . $currentParsingDate->format('Y-m-d H'));
        try {
            while($currentParsingDate <= $nowDate) {
                $output->writeln(sprintf('Processing archive for the date : %s', $currentParsingDate->format('Y-m-d-H')));
                $response = $this->client->request('GET', sprintf('http://data.gharchive.org/%s.json.gz', $currentParsingDate->format('Y-m-d-H')));
                if ($response->getStatusCode() === 404) {
                    $output->writeln(sprintf('Archive not found for the date : %s', $currentParsingDate->format('Y-m-d-H')));
                    $currentParsingDate->modify('+1 hour'); // Be ready to get elements from the next hour
                    continue;
                }
                $data = explode(PHP_EOL, gzdecode($response->getContent())); // Get an array of JSON element
                // -1 because the last array's element is empty
                for ($i = 0, $counter = count($data)-1; $i < $counter; ++$i) {
                    $decodedLine = json_decode($data[$i]);
                    if ($githubEvent = $this->ghEventManager->getEventFromImport($decodedLine)){
                        $this->commandBus->dispatch($githubEvent);
                    }
                }

                $currentParsingDate->modify('+1 hour'); // Be ready to get elements from the next hour
            }
        } catch(Exception $e) {
            $output->writeln('Oops an error has occured : ' . $e->getMessage());

            throw $e;
        }

        return 0;
    }
}
