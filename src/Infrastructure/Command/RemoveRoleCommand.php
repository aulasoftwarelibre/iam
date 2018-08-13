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

use AulaSoftwareLibre\Iam\Application\Role\Command\RemoveRole;
use AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\RoleViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\ScopeViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\RoleView;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\ScopeView;
use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\Exception\MessageDispatchException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RemoveRoleCommand extends Command
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

    public function __construct(CommandBus $commandBus, ScopeViews $scopeViews, RoleViews $roleViews)
    {
        parent::__construct();

        $this->commandBus = $commandBus;
        $this->scopeViews = $scopeViews;
        $this->roleViews = $roleViews;
    }

    protected function configure()
    {
        $this
            ->setName('iam:role:remove')
            ->setDescription('Remove a role')
            ->setHelp(<<<'EOF'
The <info>%command.name%</info> command removes a role.

The role must be indicated by its scope and its name.
EOF
            )
            ->addArgument('scope', InputArgument::REQUIRED, 'Scope alias.')
            ->addArgument('role', InputArgument::REQUIRED, 'Role name.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $scopeAlias = $input->getArgument('scope');
        $scopeViews = $this->scopeViews->ofAlias($scopeAlias);
        if (!$scopeViews instanceof ScopeView) {
            $io->error(sprintf('Scope alias "%s" not found.', $scopeAlias));

            return;
        }

        $roleName = $input->getArgument('role');
        $roleView = $this->roleViews->ofScopeIdAndRoleName($scopeViews->getId(), $roleName);
        if (!$roleView instanceof RoleView) {
            $io->error(sprintf('Role name "%s" not found.', $roleName));

            return;
        }

        $roleId = RoleId::fromString($roleView->getId());

        try {
            try {
                $this->commandBus->dispatch(
                    RemoveRole::with(
                        $roleId
                    )
                );
            } catch (MessageDispatchException $e) {
                throw $e->getPrevious();
            }

            $io->success('The role was removed');
        } catch (\Exception $e) {
            $io->error($e->getMessage());
        }
    }
}
