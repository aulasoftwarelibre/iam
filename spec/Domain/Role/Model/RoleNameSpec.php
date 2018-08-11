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

namespace spec\AulaSoftwareLibre\Iam\Domain\Role\Model;

use PhpSpec\ObjectBehavior;

class RoleNameSpec extends ObjectBehavior
{
    public function it_accepts_valid_names()
    {
        $this->beConstructedWith('ROLE_SCOPE_NAME');
        $this->shouldNotThrow(\InvalidArgumentException::class)
            ->duringInstantiation();
    }

    public function it_rejects_invalid_names()
    {
        $invalidNames = ['ROLE_NAME', 'SCOPE_NAME', 'NAME', 'ROLE NAME'];

        foreach ($invalidNames as $invalidName) {
            $this->beConstructedWith($invalidName);
            $this->shouldThrow(\InvalidArgumentException::class)
                ->duringInstantiation();
        }
    }
}
