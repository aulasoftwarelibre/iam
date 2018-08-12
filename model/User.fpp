namespace AulaSoftwareLibre\Iam\Domain\User\Model {
    data UserId = UserId deriving (Uuid);
    data Username = String deriving (ToString, FromString, Equals);
}

namespace AulaSoftwareLibre\Iam\Domain\User\Event {
    data UserWasCreated = UserWasCreated {
        \AulaSoftwareLibre\Iam\Domain\User\Model\UserId $userId,
        \AulaSoftwareLibre\Iam\Domain\User\Model\Username $username
    } deriving (AggregateChanged);

    data UserWasPromoted = UserWasPromoted {
        \AulaSoftwareLibre\Iam\Domain\User\Model\UserId $userId,
        \AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId $roleId
    } deriving (AggregateChanged);

    data UserWasDemoted = UserWasDemoted {
        \AulaSoftwareLibre\Iam\Domain\User\Model\UserId $userId,
        \AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId $roleId
    } deriving (AggregateChanged);
}

namespace AulaSoftwareLibre\Iam\Application\User\Command {
    data RegisterUser = RegisterUser {
        \AulaSoftwareLibre\Iam\Domain\User\Model\UserId $userId,
        \AulaSoftwareLibre\Iam\Domain\User\Model\Username $username
    } deriving (Command);

    data PromoteUser = PromoteUser {
        \AulaSoftwareLibre\Iam\Domain\User\Model\UserId $userId,
        \AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId $roleId
    } deriving (Command);

    data DemoteUser = DemoteUser {
        \AulaSoftwareLibre\Iam\Domain\User\Model\UserId $userId,
        \AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId $roleId
    } deriving (Command);
}
