<?php

/**
 * This file is part of slick/cqrs-tools
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\CQRSTools\Domain\Event;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Slick\CQRSTools\Domain\Event\EventStream;
use PhpSpec\ObjectBehavior;
use Slick\CQRSTools\Event;

/**
 * EventStreamSpec specs
 *
 * @package spec\Slick\CQRSTools\Domain\Event
 */
class EventStreamSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(EventStream::class);
    }

    function it_wraps_a_list_of_events(Event $event)
    {
        $this->add($event)->shouldBe($this->getWrappedObject());
        $this->asArray()->shouldBe([$event]);
    }

    function it_can_be_iterated()
    {
        $this->shouldBeAnInstanceOf(IteratorAggregate::class);
        $this->getIterator()->shouldBeAnInstanceOf(ArrayIterator::class);
    }

    function it_can_be_counted(Event $event, Event $event2)
    {
        $this->beConstructedWith([$event, $event2]);
        $this->shouldBeAnInstanceOf(Countable::class);
        $this->count()->shouldBe(2);
        $this->isEmpty()->shouldBe(false);
    }

    function it_can_retrieve_first_event(Event $event, Event $event2)
    {
        $this->beConstructedWith([$event, $event2]);
        $this->first()->shouldBe($event);
    }

    function it_can_retrieve_last_event(Event $event, Event $event2)
    {
        $this->beConstructedWith([$event, $event2]);
        $this->last()->shouldBe($event2);
    }

    function it_returns_null_for_first_call_when_empty()
    {
        $this->first()->shouldBe(null);
    }
}
