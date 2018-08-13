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

use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\ScopeViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\ScopeView;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ListScopeCommand extends Command
{
    /**
     * @var ScopeViews
     */
    private $scopeViews;

    public function __construct(ScopeViews $scopeViews)
    {
        parent::__construct();

        $this->scopeViews = $scopeViews;
    }

    protected function configure()
    {
        $this
            ->setName('iam:scope:list')
            ->setDescription('Show a list of available scopes')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $scopesViews = $this->scopeViews->all();

        if (empty($scopesViews)) {
            $io->error('No available scopes.');

            return;
        }

        $io->title('List of available scopes:');
        $table = new Table($output);
        $table->setHeaders(['Name', 'Alias']);

        /** @var ScopeView $scopesView */
        foreach ($scopesViews as $scopesView) {
            $table->addRow([$scopesView->getName(), $scopesView->getAlias()]);
        }

        $table->render();

        $io->success(sprintf('Total %s scope(s)', count($scopesViews)));
    }
}
