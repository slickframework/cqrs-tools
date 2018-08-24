<?php

/**
 * This file is part of slick/cqrs-tools
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\CQRSTools\Application\Projection;

use Prophecy\Argument;
use Slick\CQRSTools\Application\Projection\EventHandlingStrategy;
use Slick\CQRSTools\Application\Projection\ProjectionEventListener;
use PhpSpec\ObjectBehavior;
use Slick\CQRSTools\Application\Projection\Projector;
use Slick\CQRSTools\Domain\Event\EventId;
use Slick\CQRSTools\Domain\Projection\ProjectorState;
use Slick\CQRSTools\Domain\Projection\ProjectorStateLedger;
use Slick\CQRSTools\Event;
use Slick\CQRSTools\Event\EventListener;

/**
 * ProjectionEventListenerSpec specs
 *
 * @package spec\Slick\CQRSTools\Application\Projection
 */
class ProjectionEventListenerSpec extends ObjectBehavior
{
    /** @var ProjectorState */
    private $projectorState;

    /** @var Event */
    private $event;

    function let(
        Projector $projector,
        EventHandlingStrategy $strategy,
        ProjectorStateLedger $ledger
    ) {
        $projector->runFrom()->willReturn(Projector::RUN_FROM_BEGINNING);

        $this->projectorState = new ProjectorState($projector->getWrappedObject());
        $ledger->get($projector)->willReturn($this->projectorState);
        $ledger->update($this->projectorState)->willReturnArgument(0);

        $this->event = new SimpleEvent();
        $this->projectorState->lastEventWas($this->event);

        $this->beConstructedWith([$projector], $strategy, $ledger);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ProjectionEventListener::class);
    }

    function its_an_event_listener()
    {
        $this->shouldBeAnInstanceOf(EventListener::class);
    }

    function it_handles_published_events(
        Projector $projector,
        EventHandlingStrategy $strategy
    )
    {
        $this->handle($this->event);
        $strategy->handle($this->event, $projector)->shouldHaveBeenCalled();
    }
}

class SimpleEvent extends Event\AbstractEvent implements Event
{

    /**
     * Specify data which should be serialized to JSON
     *
     * @return mixed data which can be serialized by json_encode(),
     *               which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return '';
    }

    /**
     * Used to unserialize from a stored event
     *
     * @param mixed $data
     */
    public function unserializeEvent($data): void
    {
        // do nothing!
    }
}