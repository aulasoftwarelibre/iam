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

use Ramsey\Uuid\Uuid;

class GrantView
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $userId;
    /**
     * @var string
     */
    private $roleId;

    public function __construct(string $userId, string $roleId)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->userId = $userId;
        $this->roleId = $roleId;
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
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getRoleId(): string
    {
        return $this->roleId;
    }
}
