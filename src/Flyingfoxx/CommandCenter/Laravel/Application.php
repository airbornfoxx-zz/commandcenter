<?php
namespace Flyingfoxx\CommandCenter\Laravel;

use Flyingfoxx\CommandCenter\CommandApplication;
use Illuminate\Foundation\Application as App;
use Illuminate\Events\Dispatcher;
use Illuminate\Log\Writer;

/**
 * Implements Laravel as the CommandCenter application.
 *
 * @package Flyingfoxx\CommandCenter\Laravel
 * @author  Flyingfoxx <kyle@flyingfoxx.com>
 */
class Application implements CommandApplication
{
    /**
     * The Laravel application.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * The Laravel event dispatcher.
     *
     * @var \Illuminate\Events\Dispatcher
     */
    protected $event;

    /**
     * The Laravel log writer.
     *
     * @var \Illuminate\Log\Writer
     */
    protected $log;

    /**
     * Create a new CommandCenter application instance.
     *
     * @param App        $app
     * @param Dispatcher $event
     * @param Writer     $log
     */
    public function __construct(App $app, Dispatcher $event, Writer $log)
    {
        $this->app = $app;
        $this->event = $event;
        $this->log = $log;
    }

    /**
     * Create a new class instance within the Application.
     *
     * @param $className
     * @return mixed
     */
    public function make($className)
    {
        return $this->app->make($className);
    }

    /**
     * Fire an event within the Application.
     *
     * @param $eventName
     * @param $event
     * @return mixed
     */
    public function fireEvent($eventName, $event)
    {
        return $this->event->fire($eventName, $event);
    }

    /**
     * Log an event within the Application.
     *
     * @param $message
     * @return mixed
     */
    public function logEvent($message)
    {
        return $this->log->info($message);
    }
}
