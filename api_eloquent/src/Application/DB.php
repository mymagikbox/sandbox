<?php
declare(strict_types=1);

namespace PhpLab\Application;

use PhpLab\Application\Settings\SettingsInterface;
use Psr\Container\ContainerInterface;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

final class DB
{
    public function __construct(ContainerInterface $container)
    {
        $settings = $container->get(SettingsInterface::class);

        $databaseSettings = $settings->get('database');

        $capsule = new Capsule;

        $capsule->addConnection($databaseSettings);

        // Set the event dispatcher used by Eloquent models... (optional)
        $capsule->setEventDispatcher(new Dispatcher(new Container));
        // Make this Capsule instance available globally via static methods... (optional)
        $capsule->setAsGlobal();

        // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
        $capsule->bootEloquent();
    }
}