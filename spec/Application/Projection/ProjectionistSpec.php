<?php

/**
 * This file is part of slick/cqrs-tools
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\CQRSTools\Application\Projection;

use PhpSpec\Exception\Example\FailureException;
use Slick\CQRSTools\Application\Projection\EventHandlingStrategy;
use Slick\CQRSTools\Application\Projection\Projectionist;
use PhpSpec\ObjectBehavior;
use Slick\CQRSTools\Application\Projection\Projector;
use Slick\CQRSTools\Domain\Event\EventId;
use Slick\CQRSTools\Domain\Event\EventStore;
use Slick\CQRSTools\Domain\Event\EventStream;
use Slick\CQRSTools\Domain\Projection\ProjectorState;
use Slick\CQRSTools\Domain\Projection\ProjectorStateLedger;
use Slick\CQRSTools\Event;

/**
 * ProjectionistSpec specs
 *
 * @package spec\Slick\CQRSTools\Application\Projection
 */
class ProjectionistSpec extends ObjectBehavior
{
    /**
     * @var ProjectorState
     */
    private $projectorState;

    private $eventId;

    /**
     * @param \PhpSpec\Wrapper\Collaborator|EventStore $eventStore
     * @param \PhpSpec\Wrapper\Collaborator|ProjectorStateLedger $ledger
     * @param \PhpSpec\Wrapper\Collaborator|EventHandlingStrategy $strategy
     * @param \PhpSpec\Wrapper\Collaborator|Projector $projector
     * @param \PhpSpec\Wrapper\Collaborator|Event $event
     * @throws \ReflectionException
     */
    function let(
        EventStore $eventStore,
        ProjectorStateLedger $ledger,
        EventHandlingStrategy $strategy,
        Projector $projector,
        Event $event
    ) {
        $projector->runFrom()->willReturn(Projector::RUN_FROM_BEGINNING);
        $this->eventId = new EventId();

        $event->eventId()->willReturn($this->eventId);

        $this->projectorState = new ProjectorState($projector->getWrappedObject());
        $this->projectorState->lastEventWas($event->getWrappedObject());

        $ledger->get($projector)->willReturn($this->projectorState);
        $ledger->update($this->projectorState)->willReturn(clone($this->projectorState));

        $eventStore->allStoredEventsSince($this->eventId)->willReturn(new EventStream([$event->getWrappedObject()]));

        $this->beConstructedWith($eventStore, $ledger, $strategy);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Projectionist::class);
    }

    /**
     * @param \PhpSpec\Wrapper\Collaborator|EventHandlingStrategy $strategy
     * @param \PhpSpec\Wrapper\Collaborator|Projector $projector
     * @param \PhpSpec\Wrapper\Collaborator|Event $event
     * @throws \Exception
     */
    function it_can_play_a_list_of_projectors(
        EventHandlingStrategy $strategy,
        Projector $projector,
        Event $event
    ) {
        $this->play([$projector]);
        $strategy->handle($event, $projector)->shouldHaveBeenCalled();
    }

    function it_can_only_play_projectors_that_are_not_halted(
        EventHandlingStrategy $strategy,
        Projector $projector,
        Event $event
    ) {
        $this->projectorState->halt('Test');
        $this->play([$projector]);
        $strategy->handle($event, $projector)->shouldNotHaveBeenCalled();
    }

    function it_halts_a_project_when_handling_it_throws_an_error(
        EventHandlingStrategy $strategy,
        Projector $projector,
        Event $event
    )
    {
        $strategy->handle($event, $projector)->willThrow(new \Exception('test'));
        $this->play([$projector]);
        $reason = 'test EventId: '.$this->eventId;
        if ($this->projectorState->whyIsHalted() !== $reason) {
            throw new FailureException(
                "Expected failure reason to be '{$reason}', but got {$this->projectorState->whyIsHalted()}..."
            );
        }
    }

    function it_boots_halted_projectors(
        EventHandlingStrategy $strategy,
        Projector $projector,
        Event $event
    )
    {
        $this->projectorState->halt('test boot');
        $this->boot([$projector]);
        $strategy->handle($event, $projector)->shouldHaveBeenCalled();
    }

    function it_can_only_boot_halted_projectors(
        EventHandlingStrategy $strategy,
        Projector $projector,
        Event $event
    ) {
        $this->boot([$projector]);
        $strategy->handle($event, $projector)->shouldNotHaveBeenCalled();
    }

    function it_plays_or_boots_only_when_projector_is_not_projecting(
        EventHandlingStrategy $strategy,
        Projector $projector,
        Event $event
    ) {
        $this->projectorState->startProjecting();
        $this->boot([$projector]);
        $strategy->handle($event, $projector)->shouldNotHaveBeenCalled();
    }

    function it_retires_a_list_of_projectors(Projector $projector)
    {
        $projector->retire()->shouldBeCalled();
        $this->retire([$projector]);
        $reason = 'Projector was retired.';
        if ($this->projectorState->whyIsHalted() !== $reason) {
            throw new FailureException(
                "Expected failure reason to be '{$reason}', but got {$this->projectorState->whyIsHalted()}..."
            );
        }
    }
}
