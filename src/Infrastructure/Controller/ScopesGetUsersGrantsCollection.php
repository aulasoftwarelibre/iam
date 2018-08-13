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
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\UserViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\GrantView;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\RoleView;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\ScopeView;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\UserView;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ScopesGetUsersGrantsCollection
{
    /**
     * @var ScopeViews
     */
    private $scopeViews;
    /**
     * @var UserViews
     */
    private $userViews;
    /**
     * @var GrantViews
     */
    private $grantViews;

    public function __construct(ScopeViews $scopeViews, UserViews $userViews, GrantViews $grantViews)
    {
        $this->scopeViews = $scopeViews;
        $this->userViews = $userViews;
        $this->grantViews = $grantViews;
    }

    public function __invoke(string $id, string $userId)
    {
        $scope = $this->scopeViews->ofId($id);
        if (!$scope instanceof ScopeView) {
            throw new NotFoundHttpException(sprintf('Scope id "%s" not found.', $id));
        }

        $user = $this->userViews->ofId($userId);
        if (!$user instanceof UserView) {
            throw new NotFoundHttpException(sprintf('User id "%s" not found.', $id));
        }

        $grants = $this->grantViews->ofScopeIdAndUserId($id, $userId);

        return \array_map(function (GrantView $grantView) {
            return new RoleView(
                $grantView->getRoleId(),
                $grantView->getScopeId(),
                $grantView->getRoleName()
            );
        }, $grants);
    }
}
