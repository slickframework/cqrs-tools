<?php

/**
 * This file is part of slick/cqrs-tools
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\CQRSTools\Event;

use League\Event\EmitterInterface;
use Slick\CQRSTools\Event;

/**
 * Event Publisher
 *
 * @package Slick\CQRSTools\Event
 */
interface EventPublisher
{

    /**
     * Publishes all event generated by provided generator
     *
     * @param EventGenerator $generator
     */
    public function publishEventsFrom(EventGenerator $generator): void;

    /**
     * Publishes a single event
     *
     * @param Event $event
     */
    public function publish(Event $event): void;

    /**
     * Adds an event listener
     *
     * @param string        $event
     * @param EventListener $listener
     * @param int           $priority
     */
    public function addListener(
        string $event,
        EventListener $listener,
        int $priority = EmitterInterface::P_NORMAL
    ): void;
}