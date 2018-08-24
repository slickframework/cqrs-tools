<?php

/**
 * This file is part of slick/cqrs-tools
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\CQRSTools\Application\Projection;

use Slick\CQRSTools\Event;

/**
 * Event Handling Strategy
 *
 * @package Slick\CQRSTools\Application\Projection
 */
interface EventHandlingStrategy
{

    /**
     * Handles the event processing into projector
     *
     * @param Event $event
     * @param Projector   $projector
     */
    public function handle(Event $event, Projector $projector): void;
}
