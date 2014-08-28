<?php namespace Flyingfoxx\CommandCenter\Eventing;

use Flyingfoxx\CommandCenter\CommandApplication;

/**
 * Dispatches events within the CommandCenter application.
 *
 * @package Flyingfoxx\CommandCenter\Eventing
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @author  Flyingfoxx <kyle@flyingfoxx.com>
 */
class EventDispatcher
{
    /**
     * The CommandCenter application.
     *
     * @var \Flyingfoxx\CommandCenter\CommandApplication
     */
    protected $app;

    /**
     * Create a new CommandCenter event dispatcher.
     *
     * @param CommandApplication $app
     */
    public function __construct(CommandApplication $app)
    {
        $this->app = $app;
    }

    /**
     * Dispatch all events.
     *
     * @param array $events
     */
    public function dispatch(array $events)
    {
        foreach ($events as $event)
        {
            $eventName = $this->getEventName($event);

            $this->app->fireEvent($eventName, $event);
            $this->app->logEvent($eventName .' was fired.');
        }
    }

    /**
     * Get the formatted event name to fire and log.
     *
     * @param $event
     * @return mixed
     */
    private function getEventName($event)
    {
        return str_replace('\\', '.', get_class($event));
    }
}
