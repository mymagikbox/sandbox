<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Infrastructure\Repository\Mysql;

use PhpLab\Domain\Helper\DateTimeHelper;
use PhpLab\Modules\User\Domain\Model\RefreshToken;
use PhpLab\Modules\User\Domain\Repository\RefreshTokenRepositoryInterface;

use Cycle\ORM\EntityManagerInterface;
use Cycle\ORM\Select;
use Cycle\ORM\ORMInterface;
use Cycle\Database\DatabaseInterface;

final class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    private DatabaseInterface $dbal;

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly ORMInterface $orm,
    )
    {
        $this->dbal = $orm->getFactory()->database();
    }

    private function getSelector(): Select
    {
        $this->orm->getHeap()->clean();
        return new Select($this->orm, RefreshToken::class);
    }

    public function addToSave(object $entity): void
    {
        $this->em->persist($entity);
    }

    public function save(): void
    {
        $this->em->run();
    }

    public function findByToken(string $refreshToken): ?RefreshToken
    {
        $data = $this->getSelector()
            ->where(['refresh_token' => $refreshToken])
            ->fetchOne();
        return $data;
    }

    public function deleteExpiredTokens(int $userId): void
    {
        try {
            $this->dbal->begin();

            $this
                ->dbal
                ->delete(RefreshToken::TABLE)
                ->where('user_id', $userId)
                ->andWhere('expired_at', '<=', DateTimeHelper::createDateTime())
                ->run();

            $this->dbal->commit();
        } catch (\Throwable $e) {
            $this->dbal->rollback();

            throw new \Exception($e->getMessage());
        }
    }

    public function deleteToken(string $refreshToken): void
    {
        $this
            ->dbal
            ->delete(RefreshToken::TABLE, ['refresh_token', $refreshToken])
            ->run();
    }

    public function deleteUserTokens(int $userId): void
    {
        $this
            ->dbal
            ->delete(RefreshToken::TABLE, ['user_id', '=', $userId])
            ->run();
    }
}