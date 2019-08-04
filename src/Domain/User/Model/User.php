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

namespace AulaSoftwareLibre\Iam\Domain\User\Model;

use AulaSoftwareLibre\DDD\BaseBundle\Domain\ApplyMethodDispatcherTrait;
use AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId;
use AulaSoftwareLibre\Iam\Domain\User\Event\UserWasCreated;
use AulaSoftwareLibre\Iam\Domain\User\Event\UserWasDemoted;
use AulaSoftwareLibre\Iam\Domain\User\Event\UserWasPromoted;
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
     * @var RoleId[]|array
     */
    private $roles;

    public static function add(UserId $userId, Username $username): self
    {
        $user = new self();

        $user->recordThat(UserWasCreated::with($userId, $username));

        return $user;
    }

    public function __toString(): string
    {
        return $this->username()->value();
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function username(): Username
    {
        return $this->username;
    }

    public function promote(RoleId $roleId): void
    {
        if ($this->hasRole($roleId)) {
            return;
        }

        $this->recordThat(UserWasPromoted::with($this->userId, $roleId));
    }

    public function demote(RoleId $roleId): void
    {
        if (!$this->hasRole($roleId)) {
            return;
        }

        $this->recordThat(UserWasDemoted::with($this->userId, $roleId));
    }

    public function hasRole(RoleId $roleId): bool
    {
        return \in_array($roleId, $this->roles, false);
    }

    protected function aggregateId(): string
    {
        return $this->userId->toString();
    }

    protected function applyUserWasCreated(UserWasCreated $event): void
    {
        $this->userId = $event->userId();
        $this->username = $event->username();
        $this->roles = [];
    }

    protected function applyUserWasPromoted(UserWasPromoted $event): void
    {
        $this->roles[] = $event->roleId();
    }

    protected function applyUserWasDemoted(UserWasDemoted $event): void
    {
        $this->roles = array_filter($this->roles, function (RoleId $roleId) use ($event) {
            return !$event->roleId()->equals($roleId);
        });
    }
}
