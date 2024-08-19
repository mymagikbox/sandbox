<?php
declare(strict_types=1);

namespace PhpLab\Domain\Interface\Repository;

interface RepositoryGetListByQueryInterface
{
    /**
     * @param object $query
     * @return array
     */
    public function getListByQuery(object $query): array;
}