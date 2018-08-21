<?php

/**
 * This file is part of slick/cqrs-tools
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\CQRSTools\Domain\Event;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Slick\CQRSTools\Event;

/**
 * EventStream
 *
 * @package Slick\CQRSTools\Domain\Event
*/
final class EventStream implements IteratorAggregate, Countable
{
    /**
     * @var Event[]
     */
    private $events = [];

    /**
     * Creates an Event Stream
     *
     * @param array $events
     */
    public function __construct(array $events = [])
    {
        foreach ($events as $event) {
            $this->add($event);
        }
    }

    /**
     * Adds an event to the stream
     *
     * @param Event $event
     *
     * @return EventStream
     */
    public function add(Event $event): EventStream
    {
        array_push($this->events, $event);
        return $this;
    }

    /**
     * Returns the event list as an array
     *
     * @return Event[]
     */
    public function asArray(): array
    {
        return $this->events;
    }

    /**
     * Retrieve an external iterator
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->events);
    }

    /**
     * Count the events in this stream
     *
     * @return int
     */
    public function count()
    {
        return count($this->events);
    }

    /**
     * Returns true if this stream has no events
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->events);
    }

    /**
     * Returns the first event in stream or null if stream is empty
     *
     * @return null|Event
     */
    public function first(): ?Event
    {
        return reset($this->events) ?: null;
    }

    /**
     * Returns the first event in stream or null if stream is empty
     *
     * @return null|Event
     */
    public function last(): ?Event
    {
        return end($this->events) ?: null;
    }
}
