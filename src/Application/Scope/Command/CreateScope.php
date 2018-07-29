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

namespace AulaSoftwareLibre\Iam\Application\Scope\Command;

final class CreateScope extends \Prooph\Common\Messaging\Command
{
    use \Prooph\Common\Messaging\PayloadTrait;

    protected $messageName = 'AulaSoftwareLibre\Iam\Application\Scope\Command\CreateScope';

    public function scopeId(): \AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId
    {
        return \AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId::fromString($this->payload['scopeId']);
    }

    public function name(): \AulaSoftwareLibre\Iam\Domain\Scope\Model\Name
    {
        return \AulaSoftwareLibre\Iam\Domain\Scope\Model\Name::fromString($this->payload['name']);
    }

    public function shortName(): \AulaSoftwareLibre\Iam\Domain\Scope\Model\ShortName
    {
        return \AulaSoftwareLibre\Iam\Domain\Scope\Model\ShortName::fromString($this->payload['shortName']);
    }

    public static function with(\AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId $scopeId, \AulaSoftwareLibre\Iam\Domain\Scope\Model\Name $name, \AulaSoftwareLibre\Iam\Domain\Scope\Model\ShortName $shortName): CreateScope
    {
        return new self([
            'scopeId' => $scopeId->toString(),
            'name' => $name->toString(),
            'shortName' => $shortName->toString(),
        ]);
    }

    protected function setPayload(array $payload): void
    {
        if (!isset($payload['scopeId']) || !\is_string($payload['scopeId'])) {
            throw new \InvalidArgumentException("Key 'scopeId' is missing in payload or is not a string");
        }

        if (!isset($payload['name']) || !\is_string($payload['name'])) {
            throw new \InvalidArgumentException("Key 'name' is missing in payload or is not a string");
        }

        if (!isset($payload['shortName']) || !\is_string($payload['shortName'])) {
            throw new \InvalidArgumentException("Key 'shortName' is missing in payload or is not a string");
        }

        $this->payload = $payload;
    }
}
