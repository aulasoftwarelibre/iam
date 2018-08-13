namespace AulaSoftwareLibre\Iam\Domain\Role\Model {
    data RoleId = RoleId deriving (Uuid);
    data RoleName = String deriving (ToString, FromString, Equals) where
        _:
            | 0 === preg_match("/^ROLE_[A-Z_]+$/", $value) => "Role name must begin with \"ROLE_\" prefix";
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
