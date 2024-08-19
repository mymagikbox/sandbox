<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Command\LoginUser;

use Symfony\Component\Validator\Constraints as Assert;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "AdminAuthLoginUserCommand",
    required: ['email', 'password',],
    properties: [
        new OA\Property(property:'email', type: 'string', format: 'email', maximum: 255, minimum: 6),
        new OA\Property(property: 'password', type: 'string', maximum: 255, minimum: 6),
        new OA\Property(property: 'device_info', type: 'string', maximum: 255, nullable: true),
    ],
    type: 'object'
)]
final readonly class LoginUserCommand
{
    public function __construct(
        #[Assert\Length(min:6, max: 255)]
        #[Assert\Email(message: "user.email.not_correct")]
        #[Assert\NotBlank(message: "user.email.not_blank")]
        public string $email = '',

        #[Assert\Length(min: 6, max: 255)]
        #[Assert\NotBlank]
        public string $password = '',

        #[Assert\Length(max: 255)]
        public ?string $device_info = null
    ) {
    }
}