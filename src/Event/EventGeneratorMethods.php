<?php

/**
 * This file is part of slick/cqrs-tools
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\CQRSTools\Event;

use Slick\CQRSTools\Event;

/**
 * Event Generator Methods
 *
 * @package Slick\CQRSTools\Event
 */
trait EventGeneratorMethods
{
    /**
     * @var array|Event[]
     */
    protected $events = [];

    /**
     * Records that a domain event has occurred
     *
     * @param Event $event
     */
    public function recordThat(Event $event): void
    {
        array_push($this->events, $event);
    }

    /**
     * Releases the previously recorded events
     *
     * @return Event[]|array
     */
    public function releaseEvents(): array
    {
        $events = $this->events;
        $this->events = [];
        return $events;
    }
}
