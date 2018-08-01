namespace AulaSoftwareLibre\Iam\Domain\Role\Model {
    data RoleId = RoleId deriving (Uuid);
    data Name = String deriving (ToString, FromString, Equals);
    data Description = String deriving (ToString, FromString, Equals);
}

namespace AulaSoftwareLibre\Iam\Domain\Role\Event {
    data RoleWasAdded = RoleWasAdded {
        \AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId $roleId,
        \AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId $scopeId,
        \AulaSoftwareLibre\Iam\Domain\Role\Model\Name $name
    } deriving (AggregateChanged);

    data RoleWasRemoved = RoleWasRemoved {
        \AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId $roleId
    } deriving (AggregateChanged);

    data RoleWasDescribed = RoleWasDescribed {
        \AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId $roleId,
        \AulaSoftwareLibre\Iam\Domain\Role\Model\Description $description
    } deriving (AggregateChanged);
}

namespace AulaSoftwareLibre\Iam\Application\Role\Command {
    data AddRole = AddRole {
        \AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId $roleId,
        \AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId $scopeId,
        \AulaSoftwareLibre\Iam\Domain\Role\Model\Name $name
    } deriving (Command);

    data RemoveRole = RemoveRole {
        \AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId $roleId
    } deriving (Command);
}

