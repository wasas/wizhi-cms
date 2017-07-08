<?php

namespace Wizhi\Database;

use Wizhi\Foundation\ServiceProvider;

class DatabaseServiceProvider extends ServiceProvider
{
    public function register()
    {
        if (!isset($GLOBALS['wizhi.capsule'])) {
            return;
        }

        $this->app->singleton('capsule', function ($container) {
            $capsule = $GLOBALS['wizhi.capsule'];

            /*
             * Bring the illuminate fluent (as config) for capsule compatibility
             * if not defined in the service container.
             */
            if (!$container->bound('config')) {

                /*
                 * Retrieve the default container created by the "Capsule"
                 * in the framework bootstrap code.
                 */
                $defaultContainer = $capsule->getContainer();

                /*
                 * Pass default "config" class (Fluent) to the Wizhi container.
                 * It automatically adds "default" database connection
                 * which refers to the WordPress MySQL.
                 */
                $container->instance('config', $defaultContainer['config']);

                /*
                 * Update the "Capsule" container with the Wizhi container.
                 */
                $capsule->setContainer($container);
            }

            return $capsule;
        });
    }
}
