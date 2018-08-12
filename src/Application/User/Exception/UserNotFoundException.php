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

use AulaSoftwareLibre\Iam\Domain\User\Model\UserId;

class UserNotFoundException extends \InvalidArgumentException
{
    public static function withUserId(UserId $userId): self
    {
        return new self(sprintf('User with id %s does not exists', $userId->toString()));
    }
}
