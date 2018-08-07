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
use AulaSoftwareLibre\Iam\Domain\User\Event\UserWasCreated;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\UserViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\UserView;

class UserReadModel
{
    use ApplyMethodDispatcherTrait {
        applyMessage as public __invoke;
    }

    /**
     * @var UserViews
     */
    private $userViews;

    public function __construct(UserViews $userViews)
    {
        $this->userViews = $userViews;
    }

    public function applyUserWasCreated(UserWasCreated $event): void
    {
        $userView = new UserView(
            $event->userId()->toString(),
            $event->username()->toString(),
            $event->email()->toString()
        );

        $this->userViews->add($userView);
    }
}
