<?php
namespace Flyingfoxx\CommandCenter;

/**
 * Transports commands to their respective handlers.
 *
 * @package Flyingfoxx\CommandCenter
 * @author  Flyingfoxx <kyle@flyingfoxx.com>
 */
class MainCommandBus implements CommandBus
{
    /**
     * The CommandCenter application.
     *
     * @var CommandApplication
     */
    protected $app;

    /**
     * The CommandCenter translator.
     *
     * @var CommandTranslator
     */
    protected $translator;

    /**
     * Create a new CommandCenter main command bus instance.
     *
     * @param CommandApplication $app
     * @param CommandTranslator  $translator
     */
    public function __construct(CommandApplication $app, CommandTranslator $translator)
    {
        $this->app = $app;
        $this->translator = $translator;
    }

    /**
     * Execute a command.
     *
     * @param $command
     * @return mixed
     */
    public function execute($command)
    {
        $handler = $this->translator->toHandler($command);

        return $this->app->make($handler)->handle($command);
    }
}
