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

namespace spec\AulaSoftwareLibre\Iam\Application\Scope\Command;

use AulaSoftwareLibre\Iam\Application\Scope\Command\RenameScope;
use AulaSoftwareLibre\Iam\Application\Scope\Command\RenameScopeHandler;
use AulaSoftwareLibre\Iam\Application\Scope\Repository\Scopes;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\Scope;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeName;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\ScopeViews;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Tests\Spec\Fixtures;

class RenameScopeHandlerSpec extends ObjectBehavior
{
    public function let(Scopes $scopes, ScopeViews $scopeViews): void
    {
        $this->beConstructedWith($scopes, $scopeViews);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(RenameScopeHandler::class);
    }

    public function it_renames_a_scope(Scopes $scopes, Scope $scope): void
    {
        $newName = 'New name';

        $scopes->get(Argument::exact(ScopeId::fromString(Fixtures\Scope::SCOPE_ID)))->willReturn($scope);
        $scope->rename(Argument::exact(ScopeName::fromString($newName)));
        $scopes->save($scope)->shouldBeCalled();

        $this(RenameScope::with(
            ScopeId::fromString(Fixtures\Scope::SCOPE_ID),
            ScopeName::fromString($newName)
        ));
    }
}
