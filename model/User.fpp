namespace App\Domain\User\Model {
    data UserId = UserId deriving (Uuid);
    data Username = String deriving (ToString, FromString, Equals);
    data Email = String deriving (ToString, FromString, Equals);
}

namespace App\Domain\User\Event {
    data UserWasCreated = UserWasCreated {
        \App\Domain\User\Model\UserId $userId,
        \App\Domain\User\Model\Username $username,
        \App\Domain\User\Model\Email $email
    } deriving (AggregateChanged);
}

namespace App\Application\User\Command {
    data RegisterUser = RegisterUser {
        \App\Domain\User\Model\UserId $userId,
        \App\Domain\User\Model\Username $username,
        \App\Domain\User\Model\Email $email
    } deriving (Command);
}
