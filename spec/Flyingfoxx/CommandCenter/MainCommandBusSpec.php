<?php namespace spec\Flyingfoxx\CommandCenter;

use Flyingfoxx\CommandCenter\CommandApplication;
use Flyingfoxx\CommandCenter\CommandHandler;
use Flyingfoxx\CommandCenter\CommandTranslator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MainCommandBusSpec extends ObjectBehavior
{
    function let(CommandApplication $app, CommandTranslator $translator)
    {
        $this->beConstructedWith($app, $translator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Flyingfoxx\CommandCenter\MainCommandBus');
        $this->shouldImplement('Flyingfoxx\CommandCenter\CommandBus');

    }

    function it_executes_a_command($app, $translator, $command, CommandHandler $handler)
    {
        $translator->toHandler($command)->willReturn('CommandHandler');
        $app->make('CommandHandler')->willReturn($handler);
        $handler->handle($command)->shouldBeCalled();

        $this->execute($command);
    }
}