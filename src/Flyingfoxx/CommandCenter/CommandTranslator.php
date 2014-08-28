<?php namespace Flyingfoxx\CommandCenter;

/**
 * Interface CommandTranslator
 *
 * @package Flyingfoxx\CommandCenter
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @author  Flyingfoxx <kyle@flyingfoxx.com>
 */
interface CommandTranslator
{
    /**
     * Translate a command to its respective handler.
     *
     * @param $command
     * @return mixed
     */
    public function toHandler($command);

    /**
     * Translate a command to its respective validator.
     *
     * @param $command
     * @return mixed
     */
    public function toValidator($command);
} 