<?php namespace Flyingfoxx\CommandCenter;

/**
 * Interface CommandValidator
 *
 * @package Flyingfoxx\CommandCenter
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @author  Flyingfoxx <kyle@flyingfoxx.com>
 */
interface CommandValidator
{
    /**
     * Validate a command.
     *
     * @param $command
     * @return mixed
     */
    public function validate($command);
} 