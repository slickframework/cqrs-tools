<?php

/**
 * This file is part of slick/cqrs-tools
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\CQRSTools\Application\Projection\EventHandlingStrategy;

use Slick\CQRSTools\Application\Projection\EventHandlingStrategy;
use Slick\CQRSTools\Application\Projection\Projector;
use Slick\CQRSTools\Event;

/**
 * MethodNameStrategy
 *
 * @package Slick\CQRSTools\Application\Projection\EventHandlingStrategy
*/
final class MethodNameStrategy implements EventHandlingStrategy
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
        $classNamespaces = explode("\\", get_class($event));
        $eventName = end($classNamespaces);
        $methodName = "when{$eventName}";
        if (method_exists($projector, $methodName)) {
            $projector->$methodName($event);
            return true;
        }

        return false;
    }
}
