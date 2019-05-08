<?php

/**
 * This file is part of slick/cqrs-tools
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\CQRSTools\Application\Projection\EventHandlingStrategy;

use Slick\CQRSTools\Application\Exception\InvalidProjectorUsageException;
use Slick\CQRSTools\Application\Projection\EventHandlingStrategy;
use Slick\CQRSTools\Application\Projection\GreedyProjector;
use Slick\CQRSTools\Application\Projection\Projector;
use Slick\CQRSTools\Event;

/**
 * GreedyStrategy
 *
 * @package Slick\CQRSTools\Application\Projection\EventHandlingStrategy
*/
final class GreedyStrategy implements EventHandlingStrategy
{
    /**
     * Handles the event processing into projector
     *
     * @param Event $event
     * @param Projector $projector
     *
     * @return bool
     */
    public function handle(Event $event, Projector $projector): bool
    {
        if (! $projector instanceof GreedyProjector) {
            throw new InvalidProjectorUsageException(
                "Need to use a greedy projector with a greedy event handling strategy."
            );
        }

        $projector->handle($event);
        return true;
    }
}
