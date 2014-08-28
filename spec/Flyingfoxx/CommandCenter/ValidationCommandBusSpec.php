<?php namespace spec\Flyingfoxx\CommandCenter;

use Flyingfoxx\CommandCenter\CommandApplication;
use Flyingfoxx\CommandCenter\CommandBus;
use Flyingfoxx\CommandCenter\CommandTranslator;
use Flyingfoxx\CommandCenter\CommandValidator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ValidationCommandBusSpec extends ObjectBehavior
{
    function let(CommandBus $bus, CommandApplication $app, CommandTranslator $translator)
    {
        $this->beConstructedWith($bus, $app, $translator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Flyingfoxx\CommandCenter\ValidationCommandBus');
        $this->shouldImplement('Flyingfoxx\CommandCenter\CommandBus');
    }

    function it_executes_a_command_if_validation_succeeds($bus, $app, $translator, $command, StubValidator $validator)
    {
        $translator->toValidator($command)->shouldBeCalled()->willReturn(StubValidator::class);
        $app->make(StubValidator::class)->willReturn($validator);
        $validator->validate($command)->shouldBeCalled();
        $bus->execute($command)->shouldBeCalled();

        $this->execute($command);
    }

    function it_does_not_execute_a_command_if_validation_fails($bus, $app, $translator, $command, StubValidator $validator)
    {
        $translator->toValidator($command)->shouldBeCalled()->willReturn(StubValidator::class);
        $app->make(StubValidator::class)->willReturn($validator);
        $validator->validate($command)->willThrow('RuntimeException');
        $bus->execute($command)->shouldNotBeCalled();

        $this->shouldThrow('RuntimeException')->duringExecute($command);
    }
}

class StubValidator implements CommandValidator { public function validate($command) {} }