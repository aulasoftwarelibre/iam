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
use AulaSoftwareLibre\DDD\BaseBundle\Handlers\EventHandler;
use AulaSoftwareLibre\Iam\Application\Role\Repository\Roles;
use AulaSoftwareLibre\Iam\Application\User\Repository\Users;
use AulaSoftwareLibre\Iam\Domain\User\Event\UserWasDemoted;
use AulaSoftwareLibre\Iam\Domain\User\Event\UserWasPromoted;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\GrantViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\GrantView;

final class GrantReadModel implements EventHandler
{
    use ApplyMethodDispatcherTrait {
        applyMessage as public __invoke;
    }

    /**
     * @var GrantViews
     */
    private $grantViews;
    /**
     * @var Roles
     */
    private $roles;
    /**
     * @var Users
     */
    private $users;

    public function __construct(GrantViews $grantViews, Roles $roles, Users $users)
    {
        $this->grantViews = $grantViews;
        $this->roles = $roles;
        $this->users = $users;
    }

    public function applyUserWasPromoted(UserWasPromoted $event): void
    {
        $role = $this->roles->get($event->roleId());
        $user = $this->users->get($event->userId());

        $this->grantViews->add(new GrantView(
            $user->userId()->toString(),
            $user->username()->toString(),
            $role->scopeId()->toString(),
            $role->roleId()->toString(),
            $role->name()->toString()
        ));
    }

    public function applyUserWasDemoted(UserWasDemoted $event): void
    {
        $this->grantViews->remove(
            $event->userId()->toString(),
            $event->roleId()->toString()
        );
    }
}
