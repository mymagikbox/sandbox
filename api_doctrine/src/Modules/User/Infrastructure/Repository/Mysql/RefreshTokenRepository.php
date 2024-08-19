<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Infrastructure\Repository\Mysql;

use PhpLab\Domain\Helpers\DateTimeHelper;
use PhpLab\Modules\User\Domain\Model\RefreshToken;
use PhpLab\Modules\User\Domain\Repository\RefreshTokenRepositoryInterface;
use PhpLab\Modules\User\Domain\Model\User;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Query\Parameter;

final class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em
    )
    {
    }

    public function addToSave(RefreshToken $token): void
    {
        $this->em->persist($token);
    }

    public function save(): void
    {
        $this->em->flush();
    }

    public function deleteExpiredTokens(int $userId): void
    {
        $qb = $this->em->createQueryBuilder();

        $qb->delete(RefreshToken::class, 't')
            ->where('t.user = :user AND t.expiredAt <= :expiredAt')
            ->setParameters(new ArrayCollection([
                new Parameter('user', $userId),
                new Parameter('expiredAt', DateTimeHelper::createDateTime(), Types::DATETIME_IMMUTABLE)
            ]))
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function deleteByToken(string $refreshToken): void
    {
        AccessTokenModel::query()
            ->where([
                ['refresh_token', '=', $refreshToken],
            ])
            ->delete();
    }

    public function isTokenExist(string $refreshToken): bool
    {
        return AccessTokenModel::query()
            ->where([
                ['refresh_token', '=', $refreshToken],
            ])
            ->exists();
    }

    public function incrementRefreshCount(string $refreshToken): void
    {
        AccessTokenModel::query()
            ->where([
                ['refresh_token', '=', $refreshToken],
            ])
            ->increment('refresh_count', 1);
    }

    public function deleteUserTokens(int $userId): void
    {
        AccessTokenModel::query()
            ->where([['user_id', '=', $userId]])
            ->delete();
    }
}