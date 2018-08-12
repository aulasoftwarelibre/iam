namespace AulaSoftwareLibre\Iam\Domain\Scope\Model {
    data ScopeId = ScopeId deriving (Uuid);
    data ScopeName = String deriving (ToString, FromString, Equals);
    data ScopeAlias = String deriving (ToString, FromString, Equals) where
        _:
            | 1 === preg_match("/[^a-z]/", $value) => "Scope alias only accepts [a-z] characters";
}

namespace AulaSoftwareLibre\Iam\Domain\Scope\Event {
    data ScopeWasCreated = ScopeWasCreated {
        \AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId $scopeId,
        \AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeName $name,
        \AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeAlias $alias
    } deriving (AggregateChanged);

    data ScopeWasRenamed = ScopeWasRenamed {
        \AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId $scopeId,
        \AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeName $name
    } deriving (AggregateChanged);

    data ScopeWasRemoved = ScopeWasRemoved {
        \AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId $scopeId
    } deriving (AggregateChanged);
}

namespace AulaSoftwareLibre\Iam\Application\Scope\Command {
    data CreateScope = CreateScope {
        \AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId $scopeId,
        \AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeName $name,
        \AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeAlias $alias
    } deriving (Command);

    data RenameScope = RenameScope {
        \AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId $scopeId,
        \AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeName $name,
    } deriving (Command);

    data RemoveScope = RemoveScope {
        \AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId $scopeId,
    } deriving (Command);
}
