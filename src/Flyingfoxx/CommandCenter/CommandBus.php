<?php namespace Flyingfoxx\CommandCenter;

/**
 * Interface CommandBus
 *
 * @package Flyingfoxx\CommandCenter
 * @license http://www.opensource.org/licenses/mit-license.php MIT
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