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

namespace spec\AulaSoftwareLibre\Iam\Domain\Scope\Model;

use PhpSpec\ObjectBehavior;

class ShortNameSpec extends ObjectBehavior
{
    public function it_accepts_valid_names()
    {
        $this->beConstructedWith('validname');
        $this->shouldNotThrow(\InvalidArgumentException::class)
            ->duringInstantiation();
    }

    public function it_rejects_invalid_names()
    {
        $this->beConstructedWith('invalid name2');
        $this->shouldThrow(\InvalidArgumentException::class)
            ->duringInstantiation();
    }
}
