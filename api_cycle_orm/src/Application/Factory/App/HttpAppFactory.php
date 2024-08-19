<?php
declare(strict_types=1);

namespace PhpLab\Application\Factory\App;

use Slim\App;

final class HttpAppFactory extends BaseAppFactory
{
    public static function prepare(): App
    {
        self::buildContainer();

        return self::$container->get(App::class);
    }
}