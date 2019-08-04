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

namespace AulaSoftwareLibre\Iam\Application\Scope\Command;

use AulaSoftwareLibre\DDD\BaseBundle\MessageBus\CommandHandlerInterface;
use AulaSoftwareLibre\Iam\Application\Scope\Repository\Scopes;

final class RemoveScopeHandler implements CommandHandlerInterface
{
    /**
     * @var Scopes
     */
    private $scopes;

    public function __construct(Scopes $scopes)
    {
        $this->scopes = $scopes;
    }

    public function __invoke(RemoveScope $removeScope)
    {
        $scope = $this->scopes->get($removeScope->scopeId());
        $scope->remove();

        $this->scopes->save($scope);
    }
}
