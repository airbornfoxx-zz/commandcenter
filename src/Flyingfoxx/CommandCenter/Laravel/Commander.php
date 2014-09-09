<?php
namespace Flyingfoxx\CommandCenter\Laravel;

use App;

/**
 * Executes commands for Laravel controllers.
 *
 * @package Flyingfoxx\CommandCenter\Laravel
 * @author  Flyingfoxx <kyle@flyingfoxx.com>
 */
trait Commander
{
    /**
     * Execute a command using the CommandCenter command bus.
     *
     * @param $command
     * @return mixed
     */
    public function execute($command)
    {
        return $this->getCommandBus()->execute($command);
    }

    /**
     * Get new instance of CommandCenter command bus.
     *
     * @return mixed
     */
    private function getCommandBus()
    {
        return App::make('Flyingfoxx\CommandCenter\CommandBus');
    }
}
