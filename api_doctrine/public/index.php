<?php
declare(strict_types=1);

defined('ROOT_DIR') or define('ROOT_DIR', dirname(__DIR__));

require ROOT_DIR . '/vendor/autoload.php';

$app = \PhpLab\Application\Factory\App\HttpAppFactory::prepare();
$app->run();