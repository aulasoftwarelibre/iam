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

namespace AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Projection;

use AulaSoftwareLibre\DDD\BaseBundle\Domain\ApplyMethodDispatcherTrait;
use AulaSoftwareLibre\DDD\BaseBundle\MessageBus\EventHandlerInterface;
use AulaSoftwareLibre\Iam\Domain\Scope\Event\ScopeWasCreated;
use AulaSoftwareLibre\Iam\Domain\Scope\Event\ScopeWasRemoved;
use AulaSoftwareLibre\Iam\Domain\Scope\Event\ScopeWasRenamed;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\ScopeViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\ScopeView;

final class ScopeReadModel implements EventHandlerInterface
{
    use ApplyMethodDispatcherTrait {
        applyMessage as public __invoke;
    }

    /**
     * @var ScopeViews
     */
    private $scopeViews;

    public function __construct(ScopeViews $scopeViews)
    {
        $this->scopeViews = $scopeViews;
    }

    public function applyScopeWasCreated(ScopeWasCreated $event): void
    {
        $scopeView = new ScopeView(
            $event->scopeId()->toString(),
            $event->name()->toString(),
            $event->alias()->toString()
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
