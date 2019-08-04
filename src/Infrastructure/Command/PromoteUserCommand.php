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

use AulaSoftwareLibre\DDD\BaseBundle\MessageBus\CommandBus;
use AulaSoftwareLibre\Iam\Application\User\Command\PromoteUser;
use AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId;
use AulaSoftwareLibre\Iam\Domain\User\Model\UserId;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\RoleViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\ScopeViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\UserViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\RoleView;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\ScopeView;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\UserView;
use Prooph\ServiceBus\Exception\MessageDispatchException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class PromoteUserCommand extends Command
{
    /**
     * @var CommandBus
     */
    private $commandBus;
    /**
     * @var ScopeViews
     */
    private $scopeViews;
    /**
     * @var RoleViews
     */
    private $roleViews;
    /**
     * @var UserViews
     */
    private $userViews;

    public function __construct(
        CommandBus $commandBus,
        ScopeViews $scopeViews,
        RoleViews $roleViews,
        UserViews $userViews
    ) {
        parent::__construct();

        $this->commandBus = $commandBus;
        $this->scopeViews = $scopeViews;
        $this->roleViews = $roleViews;
        $this->userViews = $userViews;
    }

    protected function configure()
    {
        $this
            ->setName('iam:user:promote')
            ->setDescription('Promote an user')
            ->setHelp(<<<'EOF'
The <info>%command.name%</info> command promotes an user assinging roles to him.

You need to specify the scope alias and the role name.
EOF
            )
            ->addArgument('username', InputArgument::REQUIRED, 'Username')
            ->addArgument('scope', InputArgument::REQUIRED, 'Scope alias')
            ->addArgument('role', InputArgument::REQUIRED, 'Role name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $username = $input->getArgument('username');
        $scopeAlias = $input->getArgument('scope');
        $roleName = $input->getArgument('role');

        $scopeView = $this->scopeViews->ofAlias($scopeAlias);
        if (!$scopeView instanceof ScopeView) {
            $io->error(sprintf('Scope alias "%s" not found.', $scopeAlias));

            return;
        }

        $roleView = $this->roleViews->ofScopeIdAndRoleName($scopeView->getId(), $roleName);
        if (!$roleView instanceof RoleView) {
            $io->error(sprintf('Role name "%s" not found.', $roleName));

            return;
        }

        $userView = $this->userViews->ofUsername($username);
        if (!$userView instanceof UserView) {
            $io->error(sprintf('Username "%s" not found.', $username));

            return;
        }

        try {
            try {
                $this->commandBus->dispatch(
                    PromoteUser::with(
                        UserId::fromString($userView->getId()),
                        RoleId::fromString($roleView->getId())
                    )
                );
            } catch (MessageDispatchException $e) {
                throw $e->getPrevious();
            }

            $io->success('User promoted.');
        } catch (\Exception $e) {
            $io->error($e->getMessage());
        }
    }
}
