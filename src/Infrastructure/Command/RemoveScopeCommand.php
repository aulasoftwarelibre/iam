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
use AulaSoftwareLibre\Iam\Application\Scope\Command\RemoveScope;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\ScopeViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\ScopeView;
use Prooph\ServiceBus\Exception\MessageDispatchException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RemoveScopeCommand extends Command
{
    /**
     * @var CommandBus
     */
    private $commandBus;
    /**
     * @var ScopeViews
     */
    private $scopeViews;

    public function __construct(CommandBus $commandBus, ScopeViews $scopeViews)
    {
        parent::__construct();

        $this->commandBus = $commandBus;
        $this->scopeViews = $scopeViews;
    }

    protected function configure()
    {
        $this
            ->setName('iam:scope:remove')
            ->setDescription('Remove a new scope')
            ->setHelp(<<<'EOF'
The <info>%command.name%</info> command removes a scope.

The scope must be indicated by its alias.
EOF
            )
            ->addArgument('alias', InputArgument::REQUIRED, 'Scope alias.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $alias = $input->getArgument('alias');

        $scopeView = $this->scopeViews->ofAlias($alias);
        if (!$scopeView instanceof ScopeView) {
            $io->error(sprintf('Scope alias "%s" not found.', $alias));

            return;
        }

        $scopeId = ScopeId::fromString($scopeView->getId());

        try {
            try {
                $this->commandBus->dispatch(
                    RemoveScope::with(
                        $scopeId
                    )
                );
            } catch (MessageDispatchException $e) {
                throw $e->getPrevious();
            }

            $io->success('The scope was removed');
        } catch (\Exception $e) {
            $io->error($e->getMessage());
        }
    }
}
