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

namespace AulaSoftwareLibre\Iam\Domain\Scope\Model;

use AulaSoftwareLibre\DDD\Domain\ApplyMethodDispatcherTrait;
use AulaSoftwareLibre\Iam\Domain\Role\Model\Role;
use AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId;
use AulaSoftwareLibre\Iam\Domain\Role\Model\RoleName;
use AulaSoftwareLibre\Iam\Domain\Scope\Event\ScopeWasCreated;
use AulaSoftwareLibre\Iam\Domain\Scope\Event\ScopeWasRemoved;
use AulaSoftwareLibre\Iam\Domain\Scope\Event\ScopeWasRenamed;
use Prooph\EventSourcing\AggregateRoot;

class Scope extends AggregateRoot
{
    use ApplyMethodDispatcherTrait;

    /**
     * @var ScopeId
     */
    private $scopeId;
    /**
     * @var ScopeName
     */
    private $name;
    /**
     * @var ScopeAlias
     */
    private $alias;
    /**
     * @var bool
     */
    private $isRemoved;

    public static function add(ScopeId $scopeId, ScopeName $name, ScopeAlias $alias): self
    {
        $scope = new self();
        $scope->recordThat(ScopeWasCreated::with($scopeId, $name, $alias));

        return $scope;
    }

    public function __toString(): string
    {
        return $this->name->toString();
    }

    public function scopeId(): ScopeId
    {
        return $this->scopeId;
    }

    public function name(): ScopeName
    {
        return $this->name;
    }

    public function alias(): ScopeAlias
    {
        return $this->alias;
    }

    public function rename(ScopeName $name): void
    {
        if ($this->name()->equals($name)) {
            return;
        }

        $this->recordThat(ScopeWasRenamed::with($this->scopeId(), $name));
    }

    public function remove(): void
    {
        if ($this->isRemoved) {
            return;
        }

        $this->recordThat(ScopeWasRemoved::with($this->scopeId()));
    }

    public function isRemoved(): bool
    {
        return $this->isRemoved;
    }

    public function addRole(RoleId $roleId, RoleName $name): Role
    {
        return Role::add($roleId, $this->scopeId, $name);
    }

    protected function aggregateId(): string
    {
        return $this->scopeId->toString();
    }

    protected function applyScopeWasCreated(ScopeWasCreated $event): void
    {
        $this->scopeId = $event->scopeId();
        $this->name = $event->name();
        $this->alias = $event->alias();
        $this->isRemoved = false;
    }

    protected function applyScopeWasRenamed(ScopeWasRenamed $event): void
    {
        $this->name = $event->name();
    }

    protected function applyScopeWasRemoved(ScopeWasRemoved $event): void
    {
        $this->isRemoved = true;
    }
}
