<?php namespace spec\Flyingfoxx\CommandCenter\Eventing;

use Flyingfoxx\CommandCenter\Eventing\EventGenerator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EventGeneratorSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf('spec\Flyingfoxx\CommandCenter\Eventing\StubModel');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('spec\Flyingfoxx\CommandCenter\Eventing\StubModel');
    }

    function it_raises_pending_events($event)
    {
        $this->raise($event);

        $this->getPendingEvents()->shouldBe([$event]);
    }

    function it_releases_pending_events($event)
    {
        $this->raise($event);

        $this->releaseEvents()->shouldReturn([$event]);
        $this->getPendingEvents()->shouldBe([]);
    }
}

class StubModel { use EventGenerator; }