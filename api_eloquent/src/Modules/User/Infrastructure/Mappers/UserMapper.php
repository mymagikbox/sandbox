<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Infrastructure\Mappers;

use DateTimeImmutable;
use Illuminate\Support\Carbon;
use PhpLab\Modules\User\Domain\Entity\Password;
use PhpLab\Modules\User\Domain\Entity\Role;
use PhpLab\Modules\User\Domain\Entity\Status;
use PhpLab\Modules\User\Domain\User;
use PhpLab\Infrastructure\Mappers\BaseMapper;
use PhpLab\Modules\User\Infrastructure\Model\User as UserModel;

final class UserMapper extends BaseMapper
{
    /***
     * @param UserModel $model
     * @return User
     */
    public static function modelToDomain($model): User
    {
        $domain = new User(
            $model->username,
            Role::create($model->role),
            Status::create($model->status)
        );

        $domain->setId($model->id);
        $domain->setPassword(Password::create($model->password_hash, true));

        if ($model->created_at instanceof Carbon) {
            $domain->setCreatedAt(new DateTimeImmutable($model->created_at->toDayDateTimeString()));
        }
        if ($model->updated_at instanceof Carbon) {
            $domain->setUpdatedAt(new DateTimeImmutable($model->updated_at->toDayDateTimeString()));
        }
        if($model->deleted_at instanceof Carbon) {
            $domain->setDeletedAt(new DateTimeImmutable($model->deleted_at->toDayDateTimeString()));
        }

        return $domain;
    }

    /***
     * @param User $domain
     * @return UserModel
     */
    public static function domainToModel($domain): UserModel
    {
        $model = new UserModel([
            'username' => $domain->getUsername(),
            'password_hash' => $domain->getPassword()->getHash(),
            'role' => $domain->getRole()->getValue(),
            'status' => $domain->getStatus()->getValue(),
        ]);

        if($domain->getId()) {
            $model->id = $domain->getId();
            $model->created_at = $domain->getCreatedAt();
            $model->updated_at = $domain->getUpdatedAt();
            $model->deleted_at = $domain->getDeletedAt();
        }

        return $model;
    }
}