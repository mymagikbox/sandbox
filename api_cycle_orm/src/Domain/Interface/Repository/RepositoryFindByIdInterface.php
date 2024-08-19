<?php
declare(strict_types=1);

namespace PhpLab\Domain\Interface\Repository;

interface RepositoryFindByIdInterface
{
    /**
     * @param int $id
     * @throw DomainRecordNotFoundException
     */
    public function findById(int $id): ?object;
}