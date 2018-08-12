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

namespace AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Projection;

use AulaSoftwareLibre\DDD\Domain\ApplyMethodDispatcherTrait;
use AulaSoftwareLibre\Iam\Domain\User\Event\UserWasDemoted;
use AulaSoftwareLibre\Iam\Domain\User\Event\UserWasPromoted;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\GrantViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\GrantView;

class GrantReadModel
{
    use ApplyMethodDispatcherTrait {
        applyMessage as public __invoke;
    }

    /**
     * @var GrantViews
     */
    private $grantViews;

    public function __construct(GrantViews $grantViews)
    {
        $this->grantViews = $grantViews;
    }

    public function applyUserWasPromoted(UserWasPromoted $event): void
    {
        $this->grantViews->add(new GrantView(
            $event->userId()->toString(),
            $event->roleId()->toString()
        ));
    }

    public function applyUserWasDemoted(UserWasDemoted $event): void
    {
        $this->grantViews->remove(
            $event->userId()->toString(),
            $event->roleId()->toString()
        );
    }
}
