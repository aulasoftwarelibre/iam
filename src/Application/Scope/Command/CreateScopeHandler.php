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

use AulaSoftwareLibre\Iam\Application\Scope\Exception\ScopeIdAlreadyRegisteredException;
use AulaSoftwareLibre\Iam\Application\Scope\Exception\ShortNameAlreadyRegisteredException;
use AulaSoftwareLibre\Iam\Application\Scope\Repository\Scopes;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\Scope;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Scope\Repository\ScopeViews;

class CreateScopeHandler
{
    /**
     * @var Scopes
     */
    private $scopes;
    /**
     * @var ScopeViews
     */
    private $scopeViews;

    public function __construct(Scopes $scopes, ScopeViews $scopeViews)
    {
        $this->scopes = $scopes;
        $this->scopeViews = $scopeViews;
    }

    public function __invoke(CreateScope $createScope): void
    {
        $scopeId = $createScope->scopeId();
        $name = $createScope->name();
        $shortName = $createScope->shortName();

        if ($this->scopes->find($scopeId)) {
            throw ScopeIdAlreadyRegisteredException::withScopeId($scopeId);
        }

        if ($this->scopeViews->findByShortName($shortName->toString())) {
            throw ShortNameAlreadyRegisteredException::withShortName($shortName);
        }

        $scope = Scope::add(
            $scopeId,
            $name,
            $shortName
        );

        $this->scopes->save($scope);
    }
}