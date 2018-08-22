<?php

/**
 * This file is part of slick/cqrs-tools
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\CQRSTools\Domain\Projection;

use Serializable;
use Slick\CQRSTools\Application\Projection\Projector;
use Slick\CQRSTools\Domain\Event\EventId;
use Slick\CQRSTools\Domain\Projection\ProjectorState;
use PhpSpec\ObjectBehavior;
use Slick\CQRSTools\Event;

/**
 * ProjectorStateSpec specs
 *
 * @package spec\Slick\CQRSTools\Domain\Projection
 */
class ProjectorStateSpec extends ObjectBehavior
{

    private $runFrom;

    function let(Projector $projector)
    {
        $this->runFrom = Projector::RUN_FROM_BEGINNING;
        $projector->runFrom()->willReturn($this->runFrom);
        $this->beConstructedWith($projector);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ProjectorState::class);
    }

    function it_has_a_name(Projector $projector)
    {
        $this->name()->shouldBe(get_class($projector->getWrappedObject()));
    }

    function it_has_the_last_played_event(Event $event)
    {
        $eventId = new EventId();
        $event->eventId()->willReturn($eventId);

        $this->lastEvent()->shouldBeNull();
        $this->lastEventWas($event);

        $this->lastEvent()->shouldBe($eventId);
    }

    function it_is_not_halted_when_an_event_was_played(Event $event)
    {
        $this->halt("Something happened");
        $eventId = new EventId();
        $event->eventId()->willReturn($eventId);
        $this->lastEventWas($event);

        $this->isHalted()->shouldBe(false);
        $this->whyIsHalted()->shouldBeNull();
    }

    function it_can_set_projector_has_halted(Event $event)
    {
        $eventId = new EventId();
        $event->eventId()->willReturn($eventId);
        $this->lastEventWas($event);
        $this->isHalted()->shouldBe(false);

        $this->halt("Something happened");

        $this->isHalted()->shouldBe(true);
        $this->whyIsHalted()->shouldBe("Something happened");
    }

    function it_has_a_projecting_status()
    {
        $this->isProjecting()->shouldBe(false);

        $this->startProjecting();

        $this->isProjecting()->shouldBe(true);

        $this->stopProjecting();

        $this->isProjecting()->shouldBe(false);
    }

    function it_has_the_projector_mode()
    {
        $this->projectorRunsFrom()->shouldBe($this->runFrom);
    }

    function it_can_be_converted_to_json(Projector $projector)
    {
        $this->shouldBeAnInstanceOf(\JsonSerializable::class);
        $this->jsonSerialize()->shouldBe([
            'name' => get_class($projector->getWrappedObject()),
            'projectorRunsFrom' => Projector::RUN_FROM_BEGINNING,
            'lastEvent' => null,
            'projecting' => false,
            'halted' => true,
            'reason' => null
        ]);
    }
}
