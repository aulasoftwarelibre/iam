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

namespace AulaSoftwareLibre\Iam\Application\Scope\Exception;

use AulaSoftwareLibre\Iam\Domain\Scope\Model\ShortName;

class ShortNameAlreadyRegisteredException extends \InvalidArgumentException
{
    public static function withShortName(ShortName $shortName): ShortNameAlreadyRegisteredException
    {
        return new self(sprintf('Short name %s already taken', $shortName->toString()));
    }
}
