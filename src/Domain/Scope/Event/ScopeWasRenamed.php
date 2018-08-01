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

namespace AulaSoftwareLibre\Iam\Domain\Scope\Event;

final class ScopeWasRenamed extends \Prooph\EventSourcing\AggregateChanged
{
    public const MESSAGE_NAME = 'AulaSoftwareLibre\Iam\Domain\Scope\Event\ScopeWasRenamed';

    protected $messageName = self::MESSAGE_NAME;

    protected $payload = [];

    private $scopeId;
    private $name;

    public function scopeId(): \AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId
    {
        if (null === $this->scopeId) {
            $this->scopeId = \AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId::fromString($this->aggregateId());
        }

        return $this->scopeId;
    }

    public function name(): \AulaSoftwareLibre\Iam\Domain\Scope\Model\Name
    {
        if (null === $this->name) {
            $this->name = \AulaSoftwareLibre\Iam\Domain\Scope\Model\Name::fromString($this->payload['name']);
        }

        return $this->name;
    }

    public static function with(\AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId $scopeId, \AulaSoftwareLibre\Iam\Domain\Scope\Model\Name $name): ScopeWasRenamed
    {
        return new self($scopeId->toString(), [
            'name' => $name->toString(),
        ]);
    }

    protected function setPayload(array $payload): void
    {
        if (!isset($payload['name']) || !\is_string($payload['name'])) {
            throw new \InvalidArgumentException("Key 'name' is missing in payload or is not a string");
        }

        $this->payload = $payload;
    }
}
