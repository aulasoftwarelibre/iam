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

class UserView
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $username;
    /**
     * @var string
     */
    private $email;
    /**
     * @var string[]|array
     */
    private $roles;

    public function __construct(string $id, string $username, string $email)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->roles = [];
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
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
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function addRole(RoleView $roleView): void
    {
        $this->roles[] = $roleView;
    }

    public function removeRole(string $roleId): void
    {
        $this->roles = \array_filter($this->roles, function (RoleView $roleView) use ($roleId) {
            return $roleId !== $roleView->getId();
        });
    }
}
