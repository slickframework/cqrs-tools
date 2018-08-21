<?php

/**
 * This file is part of slick/cqrs-tools
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\CQRSTools\Domain\Event;

use League\Event\EventInterface;
use Prophecy\Argument;
use Slick\CQRSTools\Domain\Event\EventId;
use Slick\CQRSTools\Domain\Event\EventStore;
use Slick\CQRSTools\Domain\Event\EventStoreListener;
use PhpSpec\ObjectBehavior;
use Slick\CQRSTools\Domain\Event\StoredEvent;
use Slick\CQRSTools\Event;
use Slick\CQRSTools\Event\AbstractListener;
use Slick\CQRSTools\Event\EventListener;

/**
 * EventStoreListenerSpec specs
 *
 * @package spec\Slick\CQRSTools\Domain\Event
 */
class EventStoreListenerSpec extends ObjectBehavior
{

    function let(EventStore $eventStore)
    {
        $this->beConstructedWith($eventStore);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(EventStoreListener::class);
    }

    function its_an_event_listener()
    {
        $this->shouldBeAnInstanceOf(EventListener::class);
        $this->shouldHaveType(AbstractListener::class);
    }

    function it_appends_event_to_event_store(Event $event, EventStore $eventStore)
    {
        $event->eventId()->willReturn(new EventId());
        $event->occurredOn()->willReturn(new \DateTimeImmutable());
        $event->author()->willReturn(null);
        $event->jsonSerialize()->willReturn([]);
        $eventStore->append(Argument::type(StoredEvent::class))->shouldBeCalled()->willReturnArgument(0);

        $this->handle($event);
    }
}
