<?php namespace spec\Flyingfoxx\CommandCenter\Eventing;

use Flyingfoxx\CommandCenter\CommandApplication;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EventDispatcherSpec extends ObjectBehavior
{
    function let(CommandApplication $app)
    {
        $this->beConstructedWith($app);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Flyingfoxx\CommandCenter\Eventing\EventDispatcher');
    }

    function it_dispatches_events($app)
    {
        $event = new StubEvent;
        $eventName = str_replace('\\', '.', get_class($event));

        $app->fireEvent($eventName, $event)->shouldBeCalled();
        $app->logEvent($eventName .' was fired.')->shouldBeCalled();

        $this->dispatch([$event]);
    }
}

class StubEvent {}