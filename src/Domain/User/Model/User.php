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

namespace App\Domain\User\Model;

use App\Domain\User\Event\UserWasCreated;
use AulaSoftwareLibre\DDD\Domain\ApplyMethodDispatcherTrait;
use Prooph\EventSourcing\AggregateRoot;

class User extends AggregateRoot
{
    use ApplyMethodDispatcherTrait;

    /**
     * @var UserId
     */
    private $userId;
    /**
     * @var Username
     */
    private $username;
    /**
     * @var Email
     */
    private $email;

    public static function add(UserId $userId, Username $username, Email $email)
    {
        $user = new self();

        $user->recordThat(UserWasCreated::with($userId, $username, $email));

        return $user;
    }

    public function __toString(): string
    {
        return $this->username()->value();
    }

    public function userId()
    {
        return $this->userId;
    }

    public function username()
    {
        return $this->username;
    }

    public function email()
    {
        return $this->email;
    }

    protected function aggregateId(): string
    {
        return $this->userId->toString();
    }

    protected function applyUserWasCreated(UserWasCreated $event): void
    {
        $this->userId = $event->userId();
        $this->username = $event->username();
        $this->email = $event->email();
    }
}
