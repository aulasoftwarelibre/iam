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

use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\GrantViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\ScopeViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\UserViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\GrantView;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\ScopeView;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\UserView;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GrantsUserCommand extends Command
{
    /**
     * @var UserViews
     */
    private $userViews;
    /**
     * @var ScopeViews
     */
    private $scopeViews;
    /**
     * @var GrantViews
     */
    private $grantViews;

    public function __construct(UserViews $userViews, ScopeViews $scopeViews, GrantViews $grantViews)
    {
        parent::__construct();

        $this->userViews = $userViews;
        $this->scopeViews = $scopeViews;
        $this->grantViews = $grantViews;
    }

    protected function configure()
    {
        $this
            ->setName('iam:user:grants')
            ->setDescription('Gives all user grants in a scope.')
            ->addArgument('username', InputArgument::REQUIRED, 'Username.')
            ->addArgument('scope', InputArgument::REQUIRED, 'Scope alias.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $username = $input->getArgument('username');
        $userView = $this->userViews->ofUsername($username);
        if (!$userView instanceof UserView) {
            $io->error(sprintf('Username "%s" not found.', $username));

            return;
        }

        $scopeAlias = $input->getArgument('scope');
        $scopeViews = $this->scopeViews->ofAlias($scopeAlias);
        if (!$scopeViews instanceof ScopeView) {
            $io->error(sprintf('Scope alias "%s" not found.', $scopeAlias));

            return;
        }

        $grants = $this->grantViews->ofScopeIdAndUserId($scopeViews->getId(), $userView->getId());
        if (empty($grants)) {
            $io->error('No available roles in this scope.');

            return;
        }

        $io->title(sprintf('List of available roles in "%s" scope:', $scopeAlias));
        $table = new Table($output);
        $table->setHeaders(['Id.', 'Name']);

        /** @var GrantView $grant */
        foreach ($grants as $grant) {
            $table->addRow([$grant->getRoleId(), $grant->getRoleName()]);
        }

        $table->render();
        $io->success(sprintf('Total %s role(s)', count($grants)));
    }
}
