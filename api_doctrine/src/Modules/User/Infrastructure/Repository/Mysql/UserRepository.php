<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Infrastructure\Repository\Mysql;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PhpLab\Modules\User\Domain\Enum\UserStatus;
use PhpLab\Modules\User\Domain\Model\User;
use PhpLab\Modules\User\Domain\Query\ListUsers\ListUsersQuery;
use PhpLab\Modules\User\Domain\Repository\UserRepositoryInterface;

final class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em
    )
    {
    }

    public function hasByEmail(string $email): bool
    {
        $qb = $this->em->createQueryBuilder();

        $result = $qb->select('u')
            ->from(User::class, 'u')
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();

        return !is_null($result);
    }

    public function addToSave(User $user): void
    {
        $this->em->persist($user);
    }

    public function save(): void
    {
        $this->em->flush();
    }

    public function findById(int $id): ?User
    {
        $qb = $this->em->createQueryBuilder();

        return $qb->select('u')
            ->from(User::class, 'u')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByEmail(string $email): ?User
    {
        $qb = $this->em->createQueryBuilder();

        return $qb->select('u')
            ->from(User::class, 'u')
            ->where('u.email = :email')
            ->andWhere('u.status = :status')
            ->setParameter('email', $email)
            ->setParameter('status', UserStatus::ACTIVE->value)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param ListUsersQuery $query
    */
    public function getListUsersByQuery($query): ?array
    {
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