<?php

namespace LaraPress\Routing;

use Illuminate\Routing\RoutingServiceProvider as BaseRoutingServiceProvider;

class RoutingServiceProvider extends BaseRoutingServiceProvider
{
    /**
     * Register the router instance.
     *
     * @return void
     */
    protected function registerRouter()
    {
        $this->app['router'] = $this->app->share(function ($app) {
            return new Router($app['events'], $app['actions'], $app);
        });

        $this->app['actions']->listen('init', function() {
            if (is_admin()) {
                app('url')->forceRootUrl($_SERVER['WP_HOME']);
            }
        });
    }
}
