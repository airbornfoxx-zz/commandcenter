<?php namespace Flyingfoxx\CommandCenter;

/**
 * Interface CommandHandler
 *
 * @package Flyingfoxx\CommandCenter
 * @license http://www.opensource.org/licenses/mit-license.php MIT
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