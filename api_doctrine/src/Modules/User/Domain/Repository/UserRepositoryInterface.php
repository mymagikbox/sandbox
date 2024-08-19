<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Repository;

use PhpLab\Modules\User\Domain\Model\User;

interface UserRepositoryInterface
{
    public function hasByEmail(string $email): bool;
    public function addToSave(User $user): void;
    public function save(): void;
    public function findById(int $id): ?User;
    public function findByEmail(string $email): ?User;
    public function getListUsersByQuery($query): ?array;
}
