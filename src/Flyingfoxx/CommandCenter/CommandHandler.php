<?php
namespace Flyingfoxx\CommandCenter;

/**
 * Interface CommandHandler
 *
 * @package Flyingfoxx\CommandCenter
 * @author  Flyingfoxx <kyle@flyingfoxx.com>
 */
interface CommandHandler
{
    /**
     * Handle a command.
     *
     * @param $command
     * @return mixed
     */
    public function handle($command);
}
