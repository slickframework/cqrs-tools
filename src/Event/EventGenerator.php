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
 * Event Generator
 *
 * @package Slick\CQRSTools\Event
 */
interface EventGenerator
{

    /**
     * Registers an occurred domain event
     *
     * @param Event $event
     */
    public function recordThat(Event $event): void;

    /**
     * Releases all recorded events
     *
     * @return Event[]
     */
    public function releaseEvents(): array;
}
