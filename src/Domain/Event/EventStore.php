<?php

/**
 * This file is part of slick/cqrs-tools
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\CQRSTools\Domain\Event;

use DateTimeImmutable;
use Slick\CQRSTools\Domain\Event\Stream as EventStream;

/**
 * EventStore
 *
 * @package Slick\CQRSTools\Domain\Event
 */
interface EventStore
{

    /**
     * Appends an event to the store
     *
     * @param StoredEvent $event
     *
     * @return StoredEvent
     */
    public function append(StoredEvent $event): StoredEvent;

    /**
     * Retrieves all events form an ID or a date/time
     *
     * @param EventId|DateTimeImmutable $startPoint
     *
     * @return EventStream
     */
    public function allStoredEventsSince($startPoint): EventStream;

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
     */
    public function allStoredEvents(int $limit = 500): EventStream;
}
