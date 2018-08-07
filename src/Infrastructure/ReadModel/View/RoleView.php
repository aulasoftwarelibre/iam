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

class RoleView
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $scopeId;
    /**
     * @var string
     */
    private $name;

    /**
     * RoleView constructor.
     *
     * @param string $id
     * @param string $scopeId
     * @param string $name
     */
    public function __construct(string $id, string $scopeId, string $name)
    {
        $this->id = $id;
        $this->scopeId = $scopeId;
        $this->name = $name;
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
    public function getScopeId(): string
    {
        return $this->scopeId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
