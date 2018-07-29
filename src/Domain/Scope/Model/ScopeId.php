<?php

// this file is auto-generated by prolic/fpp
// don't edit this file manually

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

final class ScopeId
{
    private $uuid;

    public static function generate(): ScopeId
    {
        return new self(\Ramsey\Uuid\Uuid::uuid4());
    }

    public static function fromString(string $scopeId): ScopeId
    {
        return new self(\Ramsey\Uuid\Uuid::fromString($scopeId));
    }

    private function __construct(\Ramsey\Uuid\UuidInterface $scopeId)
    {
        $this->uuid = $scopeId;
    }

    public function toString(): string
    {
        return $this->uuid->toString();
    }

    public function __toString(): string
    {
        return $this->uuid->toString();
    }

    public function equals(ScopeId $other): bool
    {
        return $this->uuid->equals($other->uuid);
    }
}