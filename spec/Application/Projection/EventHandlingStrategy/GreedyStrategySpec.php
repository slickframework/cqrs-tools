<?php

/**
 * This file is part of slick/cqrs-tools
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\CQRSTools\Application\Projection\EventHandlingStrategy;

use Slick\CQRSTools\Application\Exception\InvalidProjectorUsageException;
use Slick\CQRSTools\Application\Projection\EventHandlingStrategy;
use Slick\CQRSTools\Application\Projection\EventHandlingStrategy\GreedyStrategy;
use PhpSpec\ObjectBehavior;
use Slick\CQRSTools\Application\Projection\GreedyProjector;
use Slick\CQRSTools\Application\Projection\Projector;
use Slick\CQRSTools\Event;

/**
 * GreedyStrategySpec specs
 *
 * @package spec\Slick\CQRSTools\Application\Projection\EventHandlingStrategy
 */
class GreedyStrategySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(GreedyStrategy::class);
    }

    function its_an_event_handling_strategy()
    {
        $this->shouldBeAnInstanceOf(EventHandlingStrategy::class);
    }

    function it_accepts_only_greedy_projectors(Event $event, GreedyProjector $projector)
    {
        $this->handle($event, $projector);
        $projector->handle($event)->shouldHaveBeenCalled();
    }

    function it_throws_exception_when_using_normal_projector(Event $event, Projector $projector)
    {
        $this->shouldThrow(InvalidProjectorUsageException::class)
            ->during('handle', [$event, $projector]);
    }
}
