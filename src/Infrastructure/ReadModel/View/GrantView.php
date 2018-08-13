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

namespace AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View;

class GrantView
{
    /**
     * @var string
     */
    private $userId;
    /**
     * @var string
     */
    private $username;
    /**
     * @var string
     */
    private $roleId;
    /**
     * @var string
     */
    private $roleName;
    /**
     * @var string
     */
    private $scopeId;

    public function __construct(string $userId, string $username, string $scopeId, string $roleId, string $roleName)
    {
        $this->userId = $userId;
        $this->username = $username;
        $this->roleId = $roleId;
        $this->scopeId = $scopeId;
        $this->roleName = $roleName;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getScopeId(): string
    {
        return $this->scopeId;
    }

    /**
     * @return string
     */
    public function getRoleId(): string
    {
        return $this->roleId;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getRoleName(): string
    {
        return $this->roleName;
    }
}
