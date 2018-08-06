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

namespace AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Role\Projection;

use AulaSoftwareLibre\DDD\Domain\ApplyMethodDispatcherTrait;
use AulaSoftwareLibre\DDD\Infrastructure\ReadModel\AbstractReadModel;
use AulaSoftwareLibre\Iam\Domain\Role\Event\RoleWasAdded;
use AulaSoftwareLibre\Iam\Domain\Role\Event\RoleWasRemoved;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Role\Repository\RoleViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Role\View\RoleView;

class RoleReadModel extends AbstractReadModel
{
    use ApplyMethodDispatcherTrait;

    /**
     * @var RoleViews
     */
    private $roleViews;

    public function __construct(RoleViews $roleViews)
    {
        $this->roleViews = $roleViews;

        parent::__construct($roleViews);
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
