<?php
namespace Flyingfoxx\CommandCenter;

/**
 * Translates commands to their respective destinations.
 *
 * @package Flyingfoxx\CommandCenter
 * @author  Flyingfoxx <kyle@flyingfoxx.com>
 */
class MainCommandTranslator implements CommandTranslator
{
    /**
     * Translate a command to its respective handler.
     *
     * @param $command
     * @return mixed
     * @throws HandlerNotRegisteredException
     */
    public function toHandler($command)
    {
        $class = get_class($command);
        $offset = ($this->positionAt('Command', $class)) ?: $this->positionAt('Request', $class);
        $handlerClass = substr_replace($class, 'Handler', $offset);

        if (!class_exists($handlerClass)) {
            $message = "Command handler [$handlerClass] does not exist.";
            throw new HandlerNotRegisteredException($message);
        }

        return $handlerClass;
    }

    /**
     * Translate a command to its respective validator.
     *
     * @param $command
     * @return mixed
     */
    public function toValidator($command)
    {
        $class = get_class($command);
        $offset = ($this->positionAt('Command', $class)) ?: $this->positionAt('Request', $class);

        return substr_replace($class, 'Validator', $offset);
    }

    /**
     * Find position of name in command class.
     *
     * @param $name
     * @param $class
     * @return mixed
     */
    protected function positionAt($name, $class)
    {
        return strpos($class, $name, strrpos($class, '\\'));
    }
}
