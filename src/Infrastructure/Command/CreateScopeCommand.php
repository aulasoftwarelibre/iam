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

use AulaSoftwareLibre\Iam\Application\Scope\Command\CreateScope;
use AulaSoftwareLibre\Iam\Application\Scope\Exception\ScopeAliasAlreadyRegisteredException;
use AulaSoftwareLibre\Iam\Application\Scope\Exception\ScopeIdAlreadyRegisteredException;
use AulaSoftwareLibre\Iam\Application\Scope\Repository\Scopes;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeAlias;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeName;
use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\Exception\MessageDispatchException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateScopeCommand extends Command
{
    /**
     * @var CommandBus
     */
    private $commandBus;
    /**
     * @var Scopes
     */
    private $scopes;

    public function __construct(CommandBus $commandBus, Scopes $scopeViews)
    {
        parent::__construct();

        $this->commandBus = $commandBus;
        $this->scopes = $scopeViews;
    }

    protected function configure()
    {
        $this
            ->setName('iam:scope:create')
            ->setDescription('Create a new scope')
            ->setHelp(<<<'EOF'
The <info>%command.name%</info> command creates a new scope.

The scope is composed by a name and an alias. The alias must be unique
and only lowercase letters are allowed.
EOF
            )
            ->addArgument('name', InputArgument::REQUIRED, 'Scope name')
            ->addArgument('alias', InputArgument::REQUIRED, 'Scope alias, must be unique and in lowercase letters')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $scopeId = $this->scopes->nextIdentity();
        $scopeName = ScopeName::fromString($input->getArgument('name'));
        $scopeAlias = ScopeAlias::fromString($input->getArgument('alias'));

        try {
            try {
                $this->commandBus->dispatch(
                    CreateScope::with(
                        $scopeId,
                        $scopeName,
                        $scopeAlias
                    )
                );
            } catch (MessageDispatchException $e) {
                throw $e->getPrevious();
            }

            $io->success(sprintf('A new scope was created with id [%s]', $scopeId));
        } catch (ScopeIdAlreadyRegisteredException | ScopeAliasAlreadyRegisteredException $e) {
            $io->error($e->getMessage());
        }
    }
}
