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

namespace AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Scope\Projection;

use AulaSoftwareLibre\DDD\Domain\ApplyMethodDispatcherTrait;
use AulaSoftwareLibre\DDD\Infrastructure\ReadModel\AbstractReadModel;
use AulaSoftwareLibre\Iam\Domain\Scope\Event\ScopeWasCreated;
use AulaSoftwareLibre\Iam\Domain\Scope\Event\ScopeWasRemoved;
use AulaSoftwareLibre\Iam\Domain\Scope\Event\ScopeWasRenamed;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Scope\Repository\ScopeViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Scope\View\ScopeView;

class ScopeReadModel extends AbstractReadModel
{
    use ApplyMethodDispatcherTrait;

    /**
     * @var ScopeViews
     */
    private $scopeViews;

    public function __construct(ScopeViews $scopeViews)
    {
        $this->scopeViews = $scopeViews;

        parent::__construct($scopeViews);
    }

    public function applyScopeWasCreated(ScopeWasCreated $event): void
    {
        $scopeView = new ScopeView(
            $event->scopeId()->toString(),
            $event->name()->toString(),
            $event->shortName()->toString()
        );

        $this->scopeViews->add($scopeView);
    }

    public function applyScopeWasRenamed(ScopeWasRenamed $event): void
    {
        $this->scopeViews->rename(
            $event->scopeId()->toString(),
            $event->name()->toString()
        );
    }

    public function applyScopeWasRemoved(ScopeWasRemoved $event): void
    {
        $this->scopeViews->remove($event->scopeId()->toString());
    }
}
