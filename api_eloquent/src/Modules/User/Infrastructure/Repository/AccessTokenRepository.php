<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Infrastructure\Repository;

use PhpLab\Modules\User\Domain\Repository\AccessTokenRepositoryInterface;
use PhpLab\Modules\User\Domain\AccessToken;
use PhpLab\Modules\User\Infrastructure\Model\AccessToken as AccessTokenModel;
use Illuminate\Database\Capsule\Manager as DB;
use PhpLab\Domain\Helpers\DateTimeHelper;

final class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    public function create(AccessToken $token): void
    {
        $model = new AccessTokenModel([
            'user_id'       => $token->getUser()->getId(),
            'refresh_token' => $token->getRefreshToken()->getValue(),
            'expired_at'    => $token->getRefreshToken()->getExpires(),
            'refresh_count' => $token->getRefreshCount(),
            'device_info'   => $token->getDeviceInfo(),
        ]);

        DB::transaction(function () use($model) {
            $model->save();
        });
    }

    public function deleteOldTokens(int $userId): void
    {
        $now = DateTimeHelper::createDateTime();

        AccessTokenModel::query()
            ->where([
                ['user_id', '=', $userId],
                ['expired_at', '<=', $now->format(DateTimeHelper::DEFAULT_FORMAT)]
            ])->delete();
    }

    public function deleteByToken(string $refreshToken): void
    {
        AccessTokenModel::query()
            ->where([
                ['refresh_token', '=', $refreshToken],
            ])->delete();
    }

    public function isTokenExist(string $refreshToken): bool
    {
        return AccessTokenModel::query()
            ->where([
                ['refresh_token', '=', $refreshToken],
            ])->exists();
    }

    public function incrementRefreshCount(string $refreshToken): void
    {
        AccessTokenModel::query()
            ->where([
                ['refresh_token', '=', $refreshToken],
            ])->increment('refresh_count', 1);
    }

    public function deleteUserTokens(int $userId): void
    {
        AccessTokenModel::query()
            ->where([['user_id', '=', $userId]])
            ->delete();
    }
}