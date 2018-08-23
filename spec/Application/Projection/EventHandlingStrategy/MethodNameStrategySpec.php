<?php

/**
 * This file is part of slick/cqrs-tools
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\CQRSTools\Application\Projection\EventHandlingStrategy;

use PhpSpec\Exception\Example\FailureException;
use Slick\CQRSTools\Application\Projection\EventHandlingStrategy;
use Slick\CQRSTools\Application\Projection\EventHandlingStrategy\MethodNameStrategy;
use PhpSpec\ObjectBehavior;
use Slick\CQRSTools\Application\Projection\Projector;
use Slick\CQRSTools\Event;
use Slick\CQRSTools\Event\AbstractEvent;

/**
 * MethodNameStrategySpec specs
 *
 * @package spec\Slick\CQRSTools\Application\Projection\EventHandlingStrategy
 */
class MethodNameStrategySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(MethodNameStrategy::class);
    }

    function its_an_event_handling_strategy()
    {
        $this->shouldBeAnInstanceOf(EventHandlingStrategy::class);
    }

    function it_calls_the_projector_method_with_same_name_as_the_event()
    {
        $event = new TestHasRan();
        $projector = new TestProjector();
        $this->handle($event, $projector);
        if (! $projector->run) {
            throw new FailureException(
                "Expected strategy to call projector method, but it didn't..."
            );
        }
    }
}

class TestHasRan extends AbstractEvent implements Event
{
    private $foo = 'bar';

    /**
     * Specify data which should be serialized to JSON
     *
     * @return mixed data which can be serialized by json_encode(),
     *               which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return ['foo' => $this->foo];
    }

    /**
     * Used to unserialize from a stored event
     *
     * @param mixed $data
     */
    public function unserializeEvent($data): void
    {
        $this->foo = $data->foo;
    }
}

class TestProjector implements Projector
{
    public $run = false;

    public function whenTestHasRan(TestHasRan $event)
    {
        $this->run = true;
    }

    /**
     * Starting point for this projector
     *
     * @return int
     */
    public function runFrom(): int
    {
        return Projector::RUN_FROM_BEGINNING;
    }

    /**
     * Clear all projection data
     */
    public function retire(): void
    {
        // Do nothing!
    }
}