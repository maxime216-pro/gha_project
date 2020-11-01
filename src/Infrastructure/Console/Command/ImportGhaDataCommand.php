<?php

declare(strict_types = 1);

namespace App\Infrastructure\Console\Command;

use App\Application\GhaImport\Command\CreateCommitCommentFromImportLineCommand;
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

    public function __construct(
        HttpClientInterface $client,
        MessageBusInterface $commandBus
    ) {
        parent::__construct();
        $this->client = $client;
        $this->commandBus = $commandBus;
    }

    protected function configure()
    {
        $this->setDescription('Trigger an import of GitHub Archive')
            ->setHelp('This command allows you to import GitHub archive. You are able to set the desired start date. Import from beginning if any date is specified.')
            ->addOption('startDate', 's', InputOption::VALUE_OPTIONAL, "Import starting date (required format : 'Y-m-d H')");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($rawStartDate = $input->getOption('startDate')) {
            $currentParsingDate = DateTime::createFromFormat('Y-m-d H', $rawStartDate);
        } else {
            // GitHub started to archive events at this date
            $currentParsingDate = new DateTime('2020-09-01');
        }
        $output->writeln('start date is :' . $currentParsingDate->format('Y-m-d H'));
        try {
            $response = $this->client->request('GET', 'http://data.gharchive.org/2015-01-01-12.json.gz');
            $data = explode(PHP_EOL, gzdecode($response->getContent()));
            array_pop($data); // last array's element is empty
        } catch(Exception $e) {
            $output->writeln('Oops an error has occured : ' . $e->getMessage());

            throw $e;
        }

        try {
            for ($i = 0, $counter = count($data)-1; $i < $counter; ++$i) {
                $decodedLine = json_decode($data[$i]);
                if ('CommitCommentEvent' === $decodedLine->type) {
                    $newLine = new CreateCommitCommentFromImportLineCommand(
                        new DateTime($decodedLine->payload->comment->created_at),
                        $decodedLine->payload->comment->body
                    );
                    $this->commandBus->dispatch($newLine);
                }
            }
        } catch (Exception $e) {
            $output->writeln('oups');
            throw $e;
        }

        return 0;
    }
}
