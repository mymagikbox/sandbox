<?php

namespace PhpLab\Modules\User\Adapters;

use PhpLab\Modules\Catalog\Api\Api;

final class ModuleCatalogAdapter
{
    public function __construct(
        private readonly Api $moduleCatalogApi
    )
    {
    }

    public function getSameData(): array|object
    {
        $data = $this->moduleCatalogApi->getSameData();

        // mapping data

        return [];
    }
}