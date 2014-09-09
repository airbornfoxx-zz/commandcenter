<?php
namespace Flyingfoxx\CommandCenter\Eventing;

use ReflectionClass;

/**
 * Handles events for their registered listeners.
 *
 * @package Flyingfoxx\CommandCenter\Eventing
 * @author  Flyingfoxx <kyle@flyingfoxx.com>
 */
class EventListener
{
    /**
     * Handle an event if listeners are registered.
     *
     * @param $event
     * @return mixed
     */
    public function handle($event)
    {
        $eventName = $this->getEventName($event);

        if ($this->listenerIsRegistered($eventName)) {
            return call_user_func([$this, 'when'.$eventName], $event);
        }
    }

    /**
     * Get event name to check against listeners.
     *
     * @param $event
     * @return string
     */
    protected function getEventName($event)
    {
        return (new ReflectionClass($event))->getShortName();
    }

    /**
     * Determine if listeners are registered for an event.
     *
     * @param $eventName
     * @return bool
     */
    protected function listenerIsRegistered($eventName)
    {
        $method = "when{$eventName}";

        return method_exists($this, $method);
    }
}
