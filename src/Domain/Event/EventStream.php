<?php

/**
 * This file is part of slick/cqrs-tools
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\CQRSTools\Domain\Event;

use ArrayIterator;
use Slick\CQRSTools\Event;

/**
 * EventStream
 *
 * @package Slick\CQRSTools\Domain\Event
*/
final class EventStream implements Stream
{
    /**
     * @var Event[]
     */
    private $events = [];

    /**
     * @var ArrayIterator
     */
    private $iterator;

    /**
     * Creates an Event Stream
     *
     * @param array $events
     * @throws \ReflectionException
     */
    public function __construct(array $events = [])
    {
        foreach ($events as $event) {
            $this->add($event);
        }
    }

    /**
     * iterator
     *
     * @return ArrayIterator
     */
    private function iterator(): ArrayIterator
    {
        if (null === $this->iterator) {
            $this->iterator = new ArrayIterator($this->events);
        }
        return $this->iterator;
    }

    /**
     * Adds an event to the stream
     *
     * @param Event|StoredEvent $event
     *
     * @return EventStream
     * @throws \ReflectionException
     */
    public function add(Event $event): EventStream
    {
        $this->iterator = null;
        $event = $event instanceof StoredEvent ? $event->event() : $event;
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

    /**
     * Return the current element
     *
     * @return mixed Can return any type.
     */
    public function current()
    {
        return $this->iterator()->current();
    }

    /**
     * Move forward to next element
     *
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        $this->iterator()->next();
    }

    /**
     * Return the key of the current element
     *
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return $this->iterator()->key();
    }

    /**
     * Checks if current position is valid
     *
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return $this->iterator()->valid();
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $this->iterator()->rewind();
    }
}
