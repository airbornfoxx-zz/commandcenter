<?php namespace spec\Flyingfoxx\CommandCenter;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MainCommandTranslatorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Flyingfoxx\CommandCenter\MainCommandTranslator');
        $this->shouldImplement('Flyingfoxx\CommandCenter\CommandTranslator');
    }

    function it_translates_a_command_to_its_command_handler()
    {
        $command = new StubCommand;
        $commandClass = get_class($command) . 'Handler';

        $this->toHandler($command)->shouldReturn($commandClass);
    }

    function it_translate_a_command_to_its_validation_handler()
    {
        $command = new StubCommand;
        $commandClass = get_class($command);
        $validatorClass = substr_replace($commandClass, 'Validator', strrpos($commandClass, 'Command'));

        $this->toValidator($command)->shouldReturn($validatorClass);
    }

    function it_throws_exception_if_handler_class_does_not_exist()
    {
        $command = new StubNoHandlerCommand;
        $this->shouldThrow('Flyingfoxx\CommandCenter\HandlerNotRegisteredException')->duringToHandler($command);
    }
}

class StubCommand {}
class StubCommandHandler {}
class StubNoHandlerCommand {}