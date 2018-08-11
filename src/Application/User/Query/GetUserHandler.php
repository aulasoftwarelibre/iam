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

namespace AulaSoftwareLibre\Iam\Application\User\Query;

use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\UserViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\UserView;
use React\Promise\Deferred;

class GetUserHandler
{
    /**
     * @var UserViews
     */
    private $userViews;

    public function __construct(UserViews $userViews)
    {
        $this->userViews = $userViews;
    }

    public function __invoke(GetUser $getUser, Deferred $deferred = null): ?UserView
    {
        $user = $this->userViews->get($getUser->userId()->toString());

        if (null === $deferred) {
            return $user;
        }

        $deferred->resolve($user);

        return null;
    }
}
