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

namespace AulaSoftwareLibre\Iam\Infrastructure\Controller;

use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\GrantViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\ScopeViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\ScopeView;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\UserView;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ScopesGetUsersCollection
{
    /**
     * @var ScopeViews
     */
    private $scopeViews;
    /**
     * @var GrantViews
     */
    private $grantViews;

    public function __construct(ScopeViews $scopeViews, GrantViews $grantViews)
    {
        $this->scopeViews = $scopeViews;
        $this->grantViews = $grantViews;
    }

    public function __invoke(string $id)
    {
        $scope = $this->scopeViews->ofId($id);
        if (!$scope instanceof ScopeView) {
            throw new NotFoundHttpException('Scope id "%s" not found.', $id);
        }

        $grants = $this->grantViews->distinctUsersOfScopeId($id);

        return array_map(function (array $data) {
            return new UserView($data['userId'], $data['username']);
        }, $grants);
    }
}
