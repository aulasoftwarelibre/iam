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

namespace Tests\Service\Prooph\Plugin;

use Prooph\Common\Messaging\DomainEvent;

final class CollectedMessage
{
    /** @var DomainEvent */
    private $event;

    /** @var bool */
    private $handled;

    public function __construct(DomainEvent $event, bool $handled)
    {
        $this->event = $event;
        $this->handled = $handled;
    }

    public function event(): DomainEvent
    {
        return $this->event;
    }

    public function isHandled(): bool
    {
        return $this->handled;
    }
}
