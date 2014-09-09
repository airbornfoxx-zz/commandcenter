<?php
namespace Flyingfoxx\CommandCenter;

/**
 * Interface CommandBus
 *
 * @package Flyingfoxx\CommandCenter
 * @author  Flyingfoxx <kyle@flyingfoxx.com>
 */
interface CommandBus
{
    /**
     * Execute a command.
     *
     * @param $command
     * @return mixed
     */
    public function execute($command);
}
