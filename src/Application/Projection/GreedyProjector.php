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
 * Greedy Projector
 *
 * @package Slick\CQRSTools\Application\Projection
 */
interface GreedyProjector extends Projector
{

    /**
     * Greedy projector handles all events
     *
     * @param Event $event
     */
    public function handle(Event $event): void;
}
