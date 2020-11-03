<?php

declare(strict_types = 1);

namespace App\Infrastructure\Console\Command;

use App\Domain\GhaImport\Service\GithubEventManagerInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
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

    private const BATCH_SIZE = 50;

    /** @var HttpClientInterface */
    private $client;

    /** @var MessageBusInterface */
    private $commandBus;

    /** @var GithubEventManagerInterface */
    private $ghEventManager;

    /** @var EntityManagerInterface */
    private $em;

    public function __construct(
        HttpClientInterface $client,
        MessageBusInterface $commandBus,
        GithubEventManagerInterface $ghEventManager,
        EntityManagerInterface $em
    ) {
        parent::__construct();
        $this->client = $client;
        $this->commandBus = $commandBus;
        $this->ghEventManager = $ghEventManager;
        $this->em = $em;
    }

    protected function configure()
    {
        $this->setDescription('Trigger an import of GitHub Archive')
            ->setHelp('This command allows you to import GitHub archive. You are able to set the desired start date. Import from beginning if any date is specified.')
            ->addOption('startDate', 's', InputOption::VALUE_OPTIONAL, "Import starting date (required format : 'Y-m-d G')");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $nowDate = new DateTime();
        if ($rawStartDate = $input->getOption('startDate')) {
            $currentParsingDate = DateTime::createFromFormat('Y-m-d G', $rawStartDate);
        } else {
            // GitHub started to archive events at this date
            $currentParsingDate = new DateTime('2015-01-01 00:00:00');
        }
        $output->writeln(sprintf('Process start at : %s', (new DateTime())->format('Y-m-d H:i:s')));
        $output->writeln('start date is : ' . $currentParsingDate->format('Y-m-d G'));
        try {
            while($currentParsingDate->format('Y-m-d-G') < $nowDate->format('Y-m-d-G')) {
                $output->writeln(sprintf('Processing archive for the date : %s', $currentParsingDate->format('Y-m-d-G')));
                $response = $this->client->request('GET', sprintf('http://data.gharchive.org/%s.json.gz', $currentParsingDate->format('Y-m-d-G')));
                if ($response->getStatusCode() === 404) {
                    $output->writeln(sprintf('Archive not found for the date : %s', $currentParsingDate->format('Y-m-d-G')));
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
                    if (($i % self::BATCH_SIZE) === 0) {
                        $this->em->flush();
                        $this->em->clear();
                    }
                }
                $this->em->flush();
                $this->em->clear();
                $output->writeln(sprintf('Processing done for this archive at : %s', (new DateTime())->format('Y-m-d H:i:s')));
                $currentParsingDate->modify('+1 hour'); // Be ready to get elements from the next hour
            }
        } catch(Exception $e) {
            $output->writeln('Oops an error has occured : ' . $e->getMessage());

            throw $e;
        }
        $output->writeln(sprintf('Process end at : %s', (new DateTime())->format('Y-m-d H:i:s')));

        return 0;
    }
}
