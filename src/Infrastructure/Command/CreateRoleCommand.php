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

use AulaSoftwareLibre\Iam\Application\Role\Command\AddRole;
use AulaSoftwareLibre\Iam\Application\Role\Exception\RoleIdAlreadyRegisteredException;
use AulaSoftwareLibre\Iam\Application\Role\Exception\RoleNameAlreadyExistsException;
use AulaSoftwareLibre\Iam\Application\Role\Exception\RoleNamePrefixInvalidException;
use AulaSoftwareLibre\Iam\Application\Role\Repository\Roles;
use AulaSoftwareLibre\Iam\Domain\Role\Model\RoleName;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\ScopeViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\ScopeView;
use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\Exception\MessageDispatchException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateRoleCommand extends Command
{
    /**
     * @var CommandBus
     */
    private $commandBus;
    /**
     * @var Roles
     */
    private $roles;
    /**
     * @var ScopeViews
     */
    private $scopeViews;

    public function __construct(
        CommandBus $commandBus,
        Roles $roles,
        ScopeViews $scopeViews
    ) {
        parent::__construct();

        $this->commandBus = $commandBus;
        $this->roles = $roles;
        $this->scopeViews = $scopeViews;
    }

    protected function configure()
    {
        $this
            ->setName('iam:role:create')
            ->setDescription('Create a new role')
            ->setHelp(<<<'EOF'
The <info>%command.name%</info> command creates a new role.

The role belongs to a scope and the name must be unique. The role name must be prefixed by <info>ROLE_</info> and the scope alias.

Vg.: If you want to add a role to admintration in <info>iam</info> scope, the role name should be <info>ROLE_IAM_ADMIN</info>.
EOF
            )
            ->addArgument('scope', InputArgument::REQUIRED, 'Scope alias')
            ->addArgument('role', InputArgument::REQUIRED, 'Role name, must be unique and in uppercase letters plus underscore character')
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

        try {
            $roleId = $this->roles->nextIdentity();
            $scopeId = ScopeId::fromString($scopeViews->getId());
            $roleName = RoleName::fromString($input->getArgument('role'));

            try {
                $this->commandBus->dispatch(
                    AddRole::with(
                        $roleId,
                        $scopeId,
                        $roleName
                    )
                );
            } catch (MessageDispatchException $e) {
                throw $e->getPrevious();
            }

            $io->success(sprintf('A new role was created with id [%s]', $scopeId));
        } catch (
            \InvalidArgumentException |
            RoleIdAlreadyRegisteredException |
            RoleNameAlreadyExistsException |
            RoleNamePrefixInvalidException $e
        ) {
            $io->error($e->getMessage());
        }
    }
}
