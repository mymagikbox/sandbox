<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Infrastructure\Repository\Mysql;

use Cycle\ORM\EntityManagerInterface;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\Select;
use PhpLab\Modules\User\Domain\Enum\UserStatus;
use PhpLab\Modules\User\Domain\Model\User;
use PhpLab\Modules\User\Domain\Query\ListUsers\ListUsersQuery;
use PhpLab\Modules\User\Domain\Repository\UserRepositoryInterface;

final class UserRepository implements UserRepositoryInterface
{

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly ORMInterface $orm,
    )
    {
    }

    private function getSelector(): Select
    {
        $this->orm->getHeap()->clean();
        return new Select($this->orm, User::class);
    }

    public function addToSave(object $entity): void
    {
        $this->em->persist($entity);
    }

    public function save(): void
    {
        $this->em->run();
    }

    public function hasByEmail(string $email): bool
    {
        $result = $this->getSelector()
            ->where(['email' => $email])
            ->count();

        return $result > 0;
    }

    public function findById(int $id): ?User
    {
        return $this
            ->getSelector()
            ->wherePK([$id])
            ->fetchOne();
    }

    public function findByEmail(string $email): ?User
    {
        return $this
            ->getSelector()
            ->where([
                'email' => $email,
                'status' => UserStatus::ACTIVE->value
            ])
            ->fetchOne();
    }

    /**
     * @param ListUsersQuery $query
    */
    public function getListByQuery($query): array
    {
        $this->getSelector()->scope();

        /*$userQuery = UserModel::query()
            ->filterByUsername($query->filter->username)
            ->filterByRole($query->filter->role)
            ->filterByStatus($query->filter->status)
            ->forPage($query->page->page, $query->page->limit)
            ->orderBy($query->sorter->fieldName, $query->sorter->sortDirection);

        $query->page->setTotal($userQuery->getQuery()->getCountForPagination());

        $userModels = $userQuery->get();

        return $userModels->map(function ($userModel) {
            return UserMapper::modelToDomain($userModel);
        })->all();*/
    }
}