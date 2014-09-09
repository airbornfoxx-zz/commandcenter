<?php
namespace Flyingfoxx\CommandCenter;

/**
 * Interface CommandValidator
 *
 * @package Flyingfoxx\CommandCenter
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
