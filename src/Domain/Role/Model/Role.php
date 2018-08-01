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

namespace AulaSoftwareLibre\Iam\Domain\Role\Model;

use AulaSoftwareLibre\DDD\Domain\ApplyMethodDispatcherTrait;
use AulaSoftwareLibre\Iam\Domain\Role\Event\RoleWasAdded;
use AulaSoftwareLibre\Iam\Domain\Role\Event\RoleWasDescribed;
use AulaSoftwareLibre\Iam\Domain\Role\Event\RoleWasRemoved;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId;
use Prooph\EventSourcing\AggregateRoot;

class Role extends AggregateRoot
{
    use ApplyMethodDispatcherTrait;

    /**
     * @var RoleId
     */
    private $roleId;
    /**
     * @var ScopeId
     */
    private $scopeId;
    /**
     * @var Name
     */
    private $name;
    /**
     * @var Description
     */
    private $description;
    /**
     * @var bool
     */
    private $isRemoved;

    public static function add(RoleId $roleId, ScopeId $scopeId, Name $name): self
    {
        $role = new self();
        $role->recordThat(RoleWasAdded::with($roleId, $scopeId, $name));

        return $role;
    }

    public function __toString(): string
    {
        return $this->name->toString();
    }

    /**
     * @return RoleId
     */
    public function roleId(): RoleId
    {
        return $this->roleId;
    }

    /**
     * @return ScopeId
     */
    public function scopeId(): ScopeId
    {
        return $this->scopeId;
    }

    /**
     * @return Name
     */
    public function name(): Name
    {
        return $this->name;
    }

    /**
     * @return Description
     */
    public function description(): ?Description
    {
        return $this->description;
    }

    public function describe(Description $description): void
    {
        if ($this->description->equals($description)) {
            return;
        }

        $this->recordThat(RoleWasDescribed::with($this->roleId(), $description));
    }

    public function remove(): void
    {
        if ($this->isRemoved) {
            return;
        }

        $this->recordThat(RoleWasRemoved::with($this->roleId()));
    }

    public function isRemoved(): bool
    {
        return $this->isRemoved;
    }

    protected function aggregateId(): string
    {
        return $this->roleId->toString();
    }

    protected function applyRoleWasAdded(RoleWasAdded $event): void
    {
        $this->roleId = $event->roleId();
        $this->scopeId = $event->scopeId();
        $this->name = $event->name();
        $this->description = Description::fromString('');
        $this->isRemoved = false;
    }

    protected function applyRoleWasRemoved(): void
    {
        $this->isRemoved = true;
    }

    protected function applyRoleWasDescribed(RoleWasDescribed $event): void
    {
        $this->description = $event->description();
    }
}
