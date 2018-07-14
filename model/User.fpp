namespace AulaSoftwareLibre\Iam\Domain\User\Model {
    data UserId = UserId deriving (Uuid);
    data Username = String deriving (ToString, FromString, Equals);
    data Email = String deriving (ToString, FromString, Equals);
}

namespace AulaSoftwareLibre\Iam\Domain\User\Event {
    data UserWasCreated = UserWasCreated {
        \AulaSoftwareLibre\Iam\Domain\User\Model\UserId $userId,
        \AulaSoftwareLibre\Iam\Domain\User\Model\Username $username,
        \AulaSoftwareLibre\Iam\Domain\User\Model\Email $email
    } deriving (AggregateChanged);
}

namespace AulaSoftwareLibre\Iam\Application\User\Command {
    data RegisterUser = RegisterUser {
        \AulaSoftwareLibre\Iam\Domain\User\Model\UserId $userId,
        \AulaSoftwareLibre\Iam\Domain\User\Model\Username $username,
        \AulaSoftwareLibre\Iam\Domain\User\Model\Email $email
    } deriving (Command);
}
