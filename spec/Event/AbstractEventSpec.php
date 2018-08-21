<?php

/**
 * This file is part of slick/cqrs-tools
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\CQRSTools\Event;

use DateTimeImmutable;
use Slick\CQRSTools\Domain\AggregateRootIdentifier;
use Slick\CQRSTools\Domain\Event\EventId;
use Slick\CQRSTools\Event\AbstractEvent;
use PhpSpec\ObjectBehavior;

/**
 * AbstractEventSpec specs
 *
 * @package spec\Slick\CQRSTools\Event
 */
class AbstractEventSpec extends ObjectBehavior
{

    function let()
    {
        $this->beAnInstanceOf(AcmeEvent::class);
    }

    function it_has_an_event_id()
    {
        $this->eventId()->shouldBeAnInstanceOf(EventId::class);
    }

    function it_has_an_occurrence_date()
    {
        $this->occurredOn()->shouldBeAnInstanceOf(DateTimeImmutable::class);
    }

    function it_can_be_converted_to_json()
    {
        $this->jsonSerialize()->shouldBe(['happens' => true]);
    }
}


class AcmeEvent extends AbstractEvent
{

    /**
     * @var bool
     */
    private $happens = true;

    public function __construct(AggregateRootIdentifier $author = null)
    {
        parent::__construct($author);
    }

    /**
     * @return bool
     */
    public function happens(): bool
    {
        return $this->happens;
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @return mixed data which can be serialized by json_encode(),
     *               which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return [
            'happens' => $this->happens()
        ];
    }

    /**
     * Used to unserialize from a stored event
     *
     * @param mixed $data
     */
    public function unserializeEvent($data): void
    {
        $this->happens = $data->happens;
    }
}