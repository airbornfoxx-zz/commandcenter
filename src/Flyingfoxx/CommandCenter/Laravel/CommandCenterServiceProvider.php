<?php namespace Flyingfoxx\CommandCenter\Laravel;

use Flyingfoxx\CommandCenter\ValidationCommandBus;
use Illuminate\Support\ServiceProvider;

/**
 * Class CommandCenterServiceProvider
 *
 * @package Flyingfoxx\CommandCenter
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @author  Flyingfoxx <kyle@flyingfoxx.com>
 */
class CommandCenterServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerApplication();
        $this->registerCommandTranslator();
        $this->registerCommandBus();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['commandcenter'];
    }

    /**
     * Register the desired command application binding.
     */
    private function registerApplication()
    {
        $this->app->bindShared('Flyingfoxx\CommandCenter\CommandApplication', function($app)
        {
            return $app->make('Flyingfoxx\CommandCenter\Laravel\Application');
        });
    }

    /**
     * Register the desired command translator binding.
     */
    private function registerCommandTranslator()
    {
        $this->app->bindShared('Flyingfoxx\CommandCenter\CommandTranslator', function($app)
        {
            return $app->make('Flyingfoxx\CommandCenter\MainCommandTranslator');
        });
    }

    /**
     * Register the desired command bus implementation.
     */
    private function registerCommandBus()
    {
        $this->app->bindShared('Flyingfoxx\CommandCenter\CommandBus', function ($app)
        {
            $main = $app->make('Flyingfoxx\CommandCenter\MainCommandBus');
            $application = $app->make('Flyingfoxx\CommandCenter\CommandApplication');
            $translator = $app->make('Flyingfoxx\CommandCenter\CommandTranslator');

            $validation = new ValidationCommandBus($main, $application, $translator);

            return $validation;
        });
    }
}