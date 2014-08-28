<?php namespace Flyingfoxx\CommandCenter\Laravel;

use App;

/**
 * Dispatches events for Laravel entities.
 *
 * @package Flyingfoxx\CommandCenter\Laravel
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @author  Flyingfoxx <kyle@flyingfoxx.com>
 */
trait Dispatcher
{
    /**
     * Dispatch all events for an entity.
     *
     * @param $entity
     * @return mixed
     */
    public function dispatchEventsFor($entity)
    {
        return $this->getDispatcher()->dispatch($entity->releaseEvents());
    }

    /**
     * Get new instance of CommandCenter event dispatcher.
     *
     * @return mixed
     */
    private function getDispatcher()
    {
        return App::make('Flyingfoxx\CommandCenter\Eventing\EventDispatcher');
    }
} 