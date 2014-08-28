<?php namespace spec\Flyingfoxx\CommandCenter\Eventing;

use Flyingfoxx\CommandCenter\Eventing\EventListener;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EventListenerSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf('spec\Flyingfoxx\CommandCenter\Eventing\StubEventListener');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Flyingfoxx\CommandCenter\Eventing\EventListener');
    }

    function it_handles_an_event_with_a_listener()
    {
        $event = new StubListenerEvent;

        $this->handle($event)->shouldReturn('foxx');
    }

    function it_handles_an_event_without_a_listener()
    {
        $event = new StubNoListenerEvent;

        $this->handle($event)->shouldReturn(null);
    }
}

class StubListenerEvent {}
class StubNoListenerEvent {}
class StubEventListener extends EventListener
{
    public function whenStubListenerEvent()
    {
        return 'foxx';
    }
}