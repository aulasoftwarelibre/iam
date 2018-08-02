namespace AulaSoftwareLibre\Iam\Domain\Role\Model {
    data RoleId = RoleId deriving (Uuid);
    data RoleName = String deriving (ToString, FromString, Equals);
    data RoleDescription = String deriving (ToString, FromString, Equals);
}

namespace AulaSoftwareLibre\Iam\Domain\Role\Event {
    data RoleWasAdded = RoleWasAdded {
        \AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId $roleId,
        \AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId $scopeId,
        \AulaSoftwareLibre\Iam\Domain\Role\Model\RoleName $name
    } deriving (AggregateChanged);

    data RoleWasRemoved = RoleWasRemoved {
        \AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId $roleId
    } deriving (AggregateChanged);

    data RoleWasDescribed = RoleWasDescribed {
        \AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId $roleId,
        \AulaSoftwareLibre\Iam\Domain\Role\Model\RoleDescription $description
    } deriving (AggregateChanged);
}

namespace AulaSoftwareLibre\Iam\Application\Role\Command {
    data AddRole = AddRole {
        \AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId $roleId,
        \AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId $scopeId,
        \AulaSoftwareLibre\Iam\Domain\Role\Model\RoleName $name
    } deriving (Command);

    data RemoveRole = RemoveRole {
        \AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId $roleId
    } deriving (Command);
}
