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

namespace AulaSoftwareLibre\Iam\Application\Scope\Query;

use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\ScopeViews;
use React\Promise\Deferred;

class GetScopesHandler
{
    /**
     * @var ScopeViews
     */
    private $scopeViews;

    public function __construct(ScopeViews $scopeViews)
    {
        $this->scopeViews = $scopeViews;
    }

    public function __invoke(GetScopes $getScopes, Deferred $deferred = null): array
    {
        $scopes = $this->scopeViews->findAll();

        if (null === $deferred) {
            return $scopes;
        }

        $deferred->resolve($scopes);
    }
}
