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

namespace AulaSoftwareLibre\Iam\Infrastructure\Command;

use AulaSoftwareLibre\Iam\Application\User\Command\RegisterUser;
use AulaSoftwareLibre\Iam\Application\User\Repository\Users;
use AulaSoftwareLibre\Iam\Domain\User\Model\Username;
use Prooph\ServiceBus\CommandBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RegisterUserCommand extends Command
{
    /**
     * @var CommandBus
     */
    private $commandBus;
    /**
     * @var Users
     */
    private $users;

    public function __construct(CommandBus $commandBus, Users $users)
    {
        parent::__construct();

        $this->commandBus = $commandBus;
        $this->users = $users;
    }

    protected function configure()
    {
        $this
            ->setName('iam:user:create')
            ->setDescription('Create a new user')
            ->addArgument('username', InputArgument::REQUIRED, 'Set username')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $userId = $this->users->nextIdentity();
        $username = Username::fromString($input->getArgument('username'));

        $this->commandBus->dispatch(
            RegisterUser::with(
                $userId,
                $username
            )
        );

        $io->success(sprintf('A new user was created with id [%s]', $userId));
    }
}
