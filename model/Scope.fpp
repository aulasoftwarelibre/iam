namespace AulaSoftwareLibre\Iam\Domain\Scope\Model {
    data ScopeId = ScopeId deriving (Uuid);
    data Name = String deriving (ToString, FromString, Equals);
    data ShortName = String deriving (ToString, FromString, Equals);
    data Description = String deriving (ToString, FromString, Equals);
}

namespace AulaSoftwareLibre\Iam\Domain\Scope\Event {
    data ScopeWasCreated = ScopeWasCreated {
        \AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId $scopeId,
        \AulaSoftwareLibre\Iam\Domain\Scope\Model\Name $name,
        \AulaSoftwareLibre\Iam\Domain\Scope\Model\ShortName $shortName
    } deriving (AggregateChanged);

    data ScopeWasRenamed = ScopeWasRenamed {
        \AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId $scopeId,
        \AulaSoftwareLibre\Iam\Domain\Scope\Model\Name $name
    } deriving (AggregateChanged);

    data ScopeWasRemoved = ScopeWasRemoved {
        \AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId $scopeId
    } deriving (AggregateChanged);
}

namespace AulaSoftwareLibre\Iam\Application\Scope\Command {
    data CreateScope = CreateScope {
        \AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId $scopeId,
        \AulaSoftwareLibre\Iam\Domain\Scope\Model\Name $name,
        \AulaSoftwareLibre\Iam\Domain\Scope\Model\ShortName $shortName
    } deriving (Command);

    data RenameScope = RenameScope {
        \AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId $scopeId,
        \AulaSoftwareLibre\Iam\Domain\Scope\Model\Name $name,
    } deriving (Command);

    data RemoveScope = RemoveScope {
        \AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId $scopeId,
    } deriving (Command);
}

namespace AulaSoftwareLibre\Iam\Application\Scope\Query {
    data GetScopes = GetScopes {
    } deriving (Query);
    data GetScope = GetScope {
        \AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId $scopeId,
    } deriving (Query);
}
