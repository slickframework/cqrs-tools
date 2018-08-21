<?php

/**
 * This file is part of slick/cqrs-tools
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\CQRSTools\Infrastructure\Events;

use League\Event\EmitterInterface;
use Slick\CQRSTools\Event;
use Slick\CQRSTools\Event\EventGenerator;
use Slick\CQRSTools\Event\EventPublisher;
use Slick\CQRSTools\Infrastructure\Events\LeagueEventPublisher;
use PhpSpec\ObjectBehavior;

/**
 * LeagueEventPublisherSpec specs
 *
 * @package spec\Slick\CQRSTools\Infrastructure\Events
 */
class LeagueEventPublisherSpec extends ObjectBehavior
{

    function let(EmitterInterface $emitter)
    {
        $this->beConstructedWith($emitter);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(LeagueEventPublisher::class);
        $this->shouldBeAnInstanceOf(EventPublisher::class);
    }

    function it_can_emit_generated_events(EventGenerator $eventGenerator, Event $event, EmitterInterface $emitter)
    {
        $eventGenerator->releaseEvents()->willReturn([$event]);
        $this->publishEventsFrom($eventGenerator);
        $emitter->emitBatch([$event])->shouldHaveBeenCalled();
    }

    function it_can_emit_an_event(Event $event, EmitterInterface $emitter)
    {
        $this->publish($event);
        $emitter->emit($event)->shouldHaveBeenCalled();
    }

    function it_can_register_listeners(Event\EventListener $listener, EmitterInterface $emitter)
    {
        $this->addListener('*', $listener);
        $emitter->addListener('*', $listener, EmitterInterface::P_NORMAL)->shouldHaveBeenCalled();
    }
}
