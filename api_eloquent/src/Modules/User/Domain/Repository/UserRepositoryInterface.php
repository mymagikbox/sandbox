<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Repository;

use PhpLab\Modules\User\Domain\User;

interface UserRepositoryInterface
{
    public function hasByUsername(string $username): bool;
    public function create(User $user): void;
    public function update(User $user): void;
    public function findById(int $id): ?User;
    public function deleteUser(int $id): void;
    public function findByUsername(string $username): ?User;
    public function getListUsersByQuery($query): ?array;
}
