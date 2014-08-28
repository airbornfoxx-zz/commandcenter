<?php namespace Flyingfoxx\CommandCenter;

/**
 * Translates commands to their respective destinations.
 *
 * @package Flyingfoxx\CommandCenter
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @author  Flyingfoxx <kyle@flyingfoxx.com>
 */
class MainCommandTranslator implements CommandTranslator
{
    /**
     * Translates a command to its respective handler.
     *
     * @param $command
     * @return mixed
     * @throws HandlerNotRegisteredException
     */
    public function toHandler($command)
    {
        $commandClass = get_class($command);
        $handlerClass = substr_replace($commandClass, 'CommandHandler', strrpos($commandClass, 'Command'));

        if (!class_exists($handlerClass))
        {
            $message = "Command handler [$handlerClass] does not exist.";
            throw new HandlerNotRegisteredException($message);
        }

        return $handlerClass;
    }

    /**
     * Translates a command to its respective validator.
     *
     * @param $command
     * @return mixed
     */
    public function toValidator($command)
    {
        $commandClass = get_class($command);
        return substr_replace($commandClass, 'Validator', strrpos($commandClass, 'Command'));
    }
}
