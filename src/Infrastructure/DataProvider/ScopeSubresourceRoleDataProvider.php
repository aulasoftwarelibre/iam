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

namespace AulaSoftwareLibre\Iam\Infrastructure\DataProvider;

use ApiPlatform\Core\DataProvider\SubresourceDataProviderInterface;
use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use AulaSoftwareLibre\Iam\Application\Role\Query\GetRoles;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\RoleView;
use Prooph\ServiceBus\QueryBus;

class ScopeSubresourceRoleDataProvider implements SubresourceDataProviderInterface
{
    /**
     * @var QueryBus
     */
    private $queryBus;

    public function __construct(QueryBus $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function getSubresource(string $resourceClass, array $identifiers, array $context, string $operationName = null)
    {
        if (RoleView::class !== $resourceClass) {
            throw new ResourceClassNotSupportedException(sprintf('The object manager associated with the "%s" resource class cannot be retrieved.', $resourceClass));
        }

        /** @var string|bool $scopeId */
        $scopeId = $identifiers['id']['id'] ?? false;

        if (false === $scopeId) {
            throw new \InvalidArgumentException(sprintf('Invalid identifiers array: %s', json_encode($identifiers)));
        }

        $promise = $this->queryBus->dispatch(
            GetRoles::with(
                ScopeId::fromString($scopeId)
            )
        );

        $roles = [];
        $promise->then(function ($result) use (&$roles) {
            $roles = $result;
        });

        return $roles;
    }
}
