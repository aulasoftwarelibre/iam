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

use AulaSoftwareLibre\DDD\Domain\ApplyMethodDispatcherTrait;
use AulaSoftwareLibre\Iam\Domain\Role\Event\RoleWasAdded;
use AulaSoftwareLibre\Iam\Domain\Role\Event\RoleWasRemoved;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\RoleViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\RoleView;

final class RoleReadModel
{
    use ApplyMethodDispatcherTrait {
        applyMessage as public __invoke;
    }

    /**
     * @var RoleViews
     */
    private $roleViews;

    public function __construct(RoleViews $roleViews)
    {
        $this->roleViews = $roleViews;
    }

    public function applyRoleWasAdded(RoleWasAdded $event): void
    {
        $roleView = new RoleView(
            $event->roleId()->toString(),
            $event->scopeId()->toString(),
            $event->name()->toString()
        );

        $this->roleViews->add($roleView);
    }

    public function applyRoleWasRemoved(RoleWasRemoved $event): void
    {
        $this->roleViews->remove($event->roleId()->toString());
    }
}
