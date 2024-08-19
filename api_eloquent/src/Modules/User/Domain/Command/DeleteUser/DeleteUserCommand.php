<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Command\DeleteUser;

use PhpLab\Modules\User\Domain\JwtAccessTokenPayload;
use Symfony\Component\Validator\Constraints as Assert;

final class DeleteUserCommand
{
    public function __construct(
        public readonly JwtAccessTokenPayload $jwtPayload,
        #[Assert\NotNull]
        #[Assert\Positive]
        public readonly int $userId,
    ) {
    }
}