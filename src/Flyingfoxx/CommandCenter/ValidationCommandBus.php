<?php namespace Flyingfoxx\CommandCenter;

/**
 * Transports commands to their respective validators.
 *
 * @package Flyingfoxx\CommandCenter
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @author  Flyingfoxx <kyle@flyingfoxx.com>
 */
class ValidationCommandBus implements CommandBus
{
    /**
     * The CommandCenter command bus.
     *
     * @var CommandBus
     */
    protected $bus;

    /**
     * The CommandCenter application.
     *
     * @var Application
     */
    protected $app;

    /**
     * The CommandCenter translator.
     *
     * @var CommandTranslator
     */
    protected $translator;

    /**
     * Create a new CommandCenter validation command bus instance.
     *
     * @param CommandBus     $bus
     * @param CommandApplication $app
     * @param CommandTranslator  $translator
     */
    public function __construct(CommandBus $bus, CommandApplication $app, CommandTranslator $translator)
    {
        $this->bus = $bus;
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
        $validator = $this->translator->toValidator($command);

        if (class_exists($validator))
        {
            $this->app->make($validator)->validate($command);
        }

        return $this->bus->execute($command);
    }
}
