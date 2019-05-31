<?php

namespace Tracing;

use Illuminate\Container\Container;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

/**
 * Class ServiceProvider
 *
 * @package Tracing
 */
class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Publishes configuration file.
     *
     * @return  void
     */
    public function boot(): void
    {
        $config = Config::get('tracing.' . Config::get('tracing.default'));

        $this->publishes([
            __DIR__ . '/config/tracing.php' => config_path('tracing.php'),
        ]);

        // Listen for the request handled event
        Event::listen(RequestHandled::class, function (RequestHandled $e) {
            // probably start tracing and create root span
        });

        // Listen for each logged message
        if ($config['log']) {
            Event::listen(MessageLogged::class, function (MessageLogged $e) {
                // add span for logging
            });
        }

        // Listen for each database query
        if ($config['db']) {
            DB::listen(function ($query) {
                // add span for logging

                Log::info("[DB Query] {$query->connection->getName()}", [
                    'query' => str_replace('"', "'", $query->sql),
                    'time'  => $query->time . 'ms',
                ]);
            });
        }

        $app = Container::getInstance()->get('app');

        $app->terminating(function () {
            // add span for logging
        });
    }

    /**
     * Make default config by merging the config from the package.
     *
     * @return  void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/config/tracing.php', 'tracing');

        return;
    }
}
