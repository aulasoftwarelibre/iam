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

namespace AulaSoftwareLibre\Iam\Application\User\Exception;

use AulaSoftwareLibre\Iam\Domain\User\Model\Username;

class UsernameAlreadyRegisteredException extends \InvalidArgumentException
{
    public static function withUsername(Username $username): UsernameAlreadyRegisteredException
    {
        return new self(sprintf('Username "%s" already taken', $username->toString()));
    }
}
