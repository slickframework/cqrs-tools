<?php

/**
 * This file is part of slick/cqrs-tools
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\CQRSTools\Application\Projection;

use DateTimeImmutable;
use Exception;
use Slick\CQRSTools\Domain\Event\EventStore;
use Slick\CQRSTools\Domain\Event\EventStream;
use Slick\CQRSTools\Domain\Projection\ProjectorState;
use Slick\CQRSTools\Domain\Projection\ProjectorStateLedger;

/**
 * Projectionist
 *
 * @package Slick\CQRSTools\Application\Projection
*/
final class Projectionist
{
    /**
     * @var EventStore
     */
    private $eventStore;

    /**
     * @var ProjectorStateLedger
     */
    private $ledger;

    /**
     * @var EventHandlingStrategy
     */
    private $strategy;

    /**
     * Creates a Projectionist
     *
     * @param EventStore            $eventStore
     * @param ProjectorStateLedger  $ledger
     * @param EventHandlingStrategy $strategy
     */
    public function __construct(EventStore $eventStore, ProjectorStateLedger $ledger, EventHandlingStrategy $strategy)
    {
        $this->eventStore = $eventStore;
        $this->ledger = $ledger;
        $this->strategy = $strategy;
    }

    /**
     * Plays provided list of projectors
     *
     * @param Projector[] $projectors
     * @throws Exception
     */
    public function play(array $projectors): void
    {
        foreach ($projectors as $projector) {
            $projectorState = $this->ledger->get($projector);

            // Play only when projector is not halted or being processed by other agent
            if ($projectorState->isHalted() || $projectorState->isProjecting()) {
                continue;
            }

            // If it's not halted, it already ran.
            if ($projectorState->projectorRunsFrom() === Projector::RUN_ONCE) {
                continue;
            }

            $this->project($projector, $projectorState);
        }
    }

    /**
     * Boots provided list of projectors
     *
     * @param Projector[] $projectors
     * @throws Exception
     */
    public function boot(array $projectors): void
    {
        foreach ($projectors as $projector) {
            $projectorState = $this->ledger->get($projector);

            // Play only when projector is not halted or being processed by other agent
            if (!$projectorState->isHalted() || $projectorState->isProjecting()) {
                continue;
            }

            $this->project($projector, $projectorState);
        }
    }

    /**
     * Retires provided list of projectors
     *
     * @param Projector[] $projectors
     */
    public function retire(array $projectors): void
    {
        foreach ($projectors as $projector) {
            $projectorState = $this->ledger->get($projector);
            $projectorState->halt("Projector was retired.");
            $projector->retire();
            $this->releaseProjector($projectorState);
        }
    }

    /**
     * Project stream into provided projector
     *
     * @param Projector      $projector
     * @param ProjectorState $projectorState
     *
     * @throws Exception
     */
    private function project(Projector $projector, ProjectorState $projectorState): void
    {
        $eventStream = $this->eventStream($projectorState);
        if ($eventStream->isEmpty()) {
            return;
        }

        $this->secureProjector($projectorState);
        foreach ($eventStream as $event) {
            try {
                $this->strategy->handle($event, $projector);
                $projectorState->lastEventWas($event);
            } catch (Exception $caught) {
                $projectorState->halt($caught->getMessage().' EventId: '.$event->eventId());
                break;
            }
        }
        $this->releaseProjector($projectorState);
    }

    /**
     * Retrieve the event stream for provided projector state
     *
     * @param ProjectorState $projectorState
     *
     * @return EventStream
     * @throws Exception
     */
    private function eventStream(ProjectorState $projectorState): EventStream
    {
        switch ($projectorState->projectorRunsFrom()) {
            case Projector::RUN_FROM_NOW:
                $date = new DateTimeImmutable('now');
                $eventStream = !$projectorState->lastEvent()
                    ? $this->eventStore->allStoredEventsSince($date)
                    : $this->eventStore->allStoredEventsSince($projectorState->lastEvent());
                break;

            case Projector::RUN_FROM_BEGINNING:
                $eventStream = $projectorState->lastEvent()
                    ? $this->eventStore->allStoredEventsSince($projectorState->lastEvent())
                    : $this->eventStore->allStoredEvents();
                break;

            case Projector::RUN_ONCE:
            default:
                $eventStream = $this->eventStore->allStoredEvents();
        }

        return $eventStream;
    }

    /**
     * Secure projector to avoid duplications
     *
     * @param ProjectorState $projectorState
     */
    private function secureProjector(ProjectorState $projectorState): void
    {
        $projectorState->startProjecting();
        $this->ledger->update($projectorState);
    }

    /**
     * Releases the projector so that it can be played by other agent
     *
     * @param ProjectorState $projectorState
     */
    private function releaseProjector(ProjectorState $projectorState): void
    {
        $projectorState->stopProjecting();
        $this->ledger->update($projectorState);
    }
}
