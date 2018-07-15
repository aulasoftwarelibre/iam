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

namespace AulaSoftwareLibre\Iam\Infrastructure\ReadModel\User;

use AulaSoftwareLibre\DDD\Domain\ApplyMethodDispatcherTrait;
use AulaSoftwareLibre\DDD\Infrastructure\ReadModel\AbstractReadModel;
use AulaSoftwareLibre\Iam\Domain\User\Event\UserWasCreated;
use AulaSoftwareLibre\Iam\Infrastructure\Entity\UserView;
use AulaSoftwareLibre\Iam\Infrastructure\Repository\UserViews;

class UserReadModel extends AbstractReadModel
{
    use ApplyMethodDispatcherTrait;

    /**
     * @var UserViews
     */
    private $userViews;

    public function __construct(UserViews $userViews)
    {
        $this->userViews = $userViews;

        parent::__construct($userViews);
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
