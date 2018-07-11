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

namespace Tests\Service\Prooph\Spec;

use Prooph\Common\Messaging\Message;

final class MessagePrototype
{
    /** @var callable */
    private $assertion;

    public function __construct(callable $assertion)
    {
        $this->assertion = $assertion;
    }

    public static function withName(string $name): self
    {
        return new self(function (Message $message) use ($name): bool {
            return $message->messageName() === $name;
        });
    }

    public function matches(Message $message): bool
    {
        return ($this->assertion)($message);
    }
}
