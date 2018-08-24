<?php

/**
 * This file is part of slick/cqrs-tools
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\CQRSTools\Application\Projection\ProjectionEventListener;

use DateTimeImmutable;
use Slick\CQRSTools\Domain\Event\EventId;
use Slick\CQRSTools\Domain\Event\EventStore;
use Slick\CQRSTools\Domain\Event\EventStream;
use Slick\CQRSTools\Domain\Event\StoredEvent;
use Slick\CQRSTools\Event;

/**
 * Listener Event Store
 * @package Slick\CQRSTools\Application\Projection\ProjectionEventListener
 */
final class ListenerEventStore implements EventStore
{
    /**
     * @var Event
     */
    private $event;

    /**
     * Creates a Listener Event Store
     *
     * @param Event $event
     */
    public function __construct(Event $event = null)
    {
        $this->event = $event;
    }

    /**
     * Appends an event to the store
     *
     * @param StoredEvent $event
     *
     * @return StoredEvent
     */
    public function append(StoredEvent $event): StoredEvent
    {
        $this->event = $event;
        return $event;
    }

    /**
     * Retrieves all events form an ID or a date/time
     *
     * @param EventId|DateTimeImmutable $startPoint
     *
     * @return EventStream
     * @throws \ReflectionException
     */
    public function allStoredEventsSince($startPoint): EventStream
    {
        return new EventStream([$this->event]);
    }

    /**
     * Retrieve all stored events
     *
     * For performance reasons this call SHOULD always be limited as stored events
     * can be a large collection of records retrieved from a data store. Since this
     * will return all events from the beginning it can be used with EventStore::allStoredEventsSince()
     * method to keep retrieving all events.
     *
     * @param int $limit
     *
     * @return EventStream
     * @throws \ReflectionException
     */
    public function allStoredEvents(int $limit = 500): EventStream
    {
        return new EventStream([$this->event]);
    }
}
