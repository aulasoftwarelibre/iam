<?php

declare(strict_types=1);

/*
 * This file is part of the `iam` project.
 *
 * (c) Aula de Software Libre de la UCO <aulasoftwarelibre@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Infrastructure\Command;

use Prooph\EventStore\EventStore;
use Prooph\EventStore\Stream;
use Prooph\EventStore\StreamName;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateEventStreamCommand extends Command
{
    /**
     * @var EventStore
     */
    private $eventStore;

    public function __construct(EventStore $eventStore)
    {
        parent::__construct();

        $this->eventStore = $eventStore;
    }

    protected function configure()
    {
        $this
            ->setName('event-store:event-stream:create')
            ->setDescription('Create an event stream.')
            ->addArgument('stream_name', InputArgument::OPTIONAL, 'Event stream name', 'event_stream')
            ->setHelp('This command creates the event_stream')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $streamName = $input->getArgument('stream_name');
        $io = new SymfonyStyle($input, $output);

        $this->eventStore->create(new Stream(new StreamName($streamName), new \ArrayIterator([])));
        $io->success('Event stream was created successfully.');
    }
}
