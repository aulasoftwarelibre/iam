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

use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\RoleViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\ScopeViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\RoleView;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\ScopeView;
use Prooph\ServiceBus\CommandBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ListRoleCommand extends Command
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

    public function __construct(
        CommandBus $commandBus,
        ScopeViews $scopeViews,
        RoleViews $roleViews
    ) {
        parent::__construct();

        $this->commandBus = $commandBus;
        $this->scopeViews = $scopeViews;
        $this->roleViews = $roleViews;
    }

    protected function configure()
    {
        $this
            ->setName('iam:role:list')
            ->setDescription('List all roles from a scope')
            ->setHelp(<<<'EOF'
The <info>%command.name%</info> list all available roles in a scope.
EOF
            )
            ->addArgument('scope', InputArgument::REQUIRED, 'Scope alias.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $scopeAlias = $input->getArgument('scope');
        $scopeView = $this->scopeViews->ofAlias($scopeAlias);
        if (!$scopeView instanceof ScopeView) {
            $io->error(sprintf('Scope alias "%s" not found.', $scopeAlias));

            return;
        }

        $roleViews = $this->roleViews->ofScopeId($scopeView->getId());
        if (empty($roleViews)) {
            $io->error('No available roles in this scope.');

            return;
        }

        $io->title(sprintf('List of available roles in "%s" scope:', $scopeAlias));
        $table = new Table($output);
        $table->setHeaders(['Alias']);

        /** @var RoleView $roleView */
        foreach ($roleViews as $roleView) {
            $table->addRow([$roleView->getName()]);
        }

        $table->render();

        $io->success(sprintf('Total %s role(s)', count($roleViews)));
    }
}
