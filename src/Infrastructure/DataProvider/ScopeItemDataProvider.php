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

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use AulaSoftwareLibre\Iam\Application\Scope\Query\GetScope;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\ScopeView;
use Prooph\ServiceBus\QueryBus;

class ScopeItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    /**
     * @var QueryBus
     */
    private $queryBus;

    public function __construct(QueryBus $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return ScopeView::class === $resourceClass;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?ScopeView
    {
        $promise = $this->queryBus->dispatch(
            GetScope::with(ScopeId::fromString($id))
        );

        $scope = null;
        $promise->then(function ($result) use (&$scope) {
            $scope = $result;
        });

        return $scope;
    }
}
