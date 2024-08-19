<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Infrastructure\Repository;


use PhpLab\Domain\Helpers\DateTimeHelper;
use PhpLab\Modules\User\Domain\Entity\Status;
use PhpLab\Modules\User\Domain\Query\ListUsers\ListUsersQuery;
use PhpLab\Modules\User\Domain\Repository\UserRepositoryInterface;
use PhpLab\Modules\User\Domain\User;
use PhpLab\Modules\User\Infrastructure\Mappers\UserMapper;
use PhpLab\Modules\User\Infrastructure\Model\User as UserModel;

final class UserRepository implements UserRepositoryInterface
{
    public function hasByUsername(string $username): bool
    {
        return UserModel::query()
            ->where('username', '=', $username)
            ->active()
            ->exists();
    }

    public function create(User $user): void
    {
        $model = UserMapper::domainToModel($user);

        $model->save();
    }

    public function update(User $user): void
    {
        UserModel::query()
            ->where('id', '=', $user->getId())
            ->update([
                'username'      => $user->getUsername(),
                'role'          => $user->getRole()->getValue(),
                'status'        => $user->getStatus()->getValue(),
            ]);
    }

    public function findById(int $id): ?User
    {
        $userModel = UserModel::query()
            ->where('id', '=', $id)
            ->first();

        return $userModel ? UserMapper::modelToDomain($userModel) : null;
    }

    public function findByUsername(string $username): ?User
    {
        $userModel = UserModel::query()
            ->where('username', '=', $username)
            ->active()
            ->first();

        return $userModel ? UserMapper::modelToDomain($userModel) : null;
    }

    /**
     * @param ListUsersQuery $query
    */
    public function getListUsersByQuery($query): ?array
    {
        $userQuery = UserModel::query()
            ->filterByUsername($query->filter->username)
            ->filterByRole($query->filter->role)
            ->filterByStatus($query->filter->status)
            ->forPage($query->page->page, $query->page->limit)
            ->orderBy($query->sorter->fieldName, $query->sorter->sortDirection);

        $query->page->setTotal($userQuery->getQuery()->getCountForPagination());

        $userModels = $userQuery->get();

        return $userModels->map(function ($userModel) {
            return UserMapper::modelToDomain($userModel);
        })->all();
    }

    public function deleteUser(int $id): void
    {
        UserModel::query()
            ->where('id', '=', $id)
            ->update([
                'status'     => Status::STATUS_DELETED,
                'deleted_at' => DateTimeHelper::createDateTime()
            ]);
    }
}