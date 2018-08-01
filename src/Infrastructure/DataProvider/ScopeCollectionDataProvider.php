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

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use AulaSoftwareLibre\Iam\Application\Scope\Query\GetScopes;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Scope\View\ScopeView;
use Prooph\ServiceBus\QueryBus;
use React\Promise\Promise;

class ScopeCollectionDataProvider implements CollectionDataProviderInterface, RestrictedDataProviderInterface
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

    public function getCollection(string $resourceClass, string $operationName = null)
    {
        /** @var Promise $promise */
        $promise = $this->queryBus->dispatch(
            new GetScopes()
        );

        $scopes = [];
        $promise->then(function ($result) use (&$scopes) {
            $scopes = $result;
        });

        return $scopes;
    }
}
