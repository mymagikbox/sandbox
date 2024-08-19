<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Repository;

use PhpLab\Modules\User\Domain\Model\User;
use PhpLab\Domain\Interface\Repository\RepositoryFindByIdInterface;
use PhpLab\Domain\Interface\Repository\RepositoryGetListByQueryInterface;
use PhpLab\Domain\Interface\Repository\RepositorySaveInterface;

interface UserRepositoryInterface extends
    RepositoryFindByIdInterface,
    RepositoryGetListByQueryInterface,
    RepositorySaveInterface
{
    public function hasByEmail(string $email): bool;
    public function findByEmail(string $email): ?User;
}
