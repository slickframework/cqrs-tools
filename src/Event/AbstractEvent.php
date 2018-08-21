<?php

/**
 * This file is part of slick/cqrs-tools
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\CQRSTools\Event;

use DateTimeImmutable;
use Exception;
use League\Event\AbstractEvent as LeagueEvent;
use Slick\CQRSTools\Domain\AggregateRootIdentifier;
use Slick\CQRSTools\Domain\Events\EventId;
use Slick\CQRSTools\Event;
use Slick\CQRSTools\Exception\FailToCreateDateException;

/**
 * Abstract Event
 *
 * @package Slick\CQRSTools\Event
 */
abstract class AbstractEvent extends LeagueEvent implements Event
{

    /**
     * @var EventId
     */
    protected $eventId;

    /**
     * @var DateTimeImmutable
     */
    protected $occurredOn;

    /**
     * @var null|AggregateRootIdentifier
     */
    protected $author;

    /**
     * Creates an Abstract Event
     *
     * @param AggregateRootIdentifier|null $author
     */
    public function __construct(AggregateRootIdentifier $author = null)
    {
        $this->eventId = new EventId();
        $this->author = $author;
        try {
            $this->occurredOn = new DateTimeImmutable();
        } catch (Exception $caught) {
            throw new FailToCreateDateException($caught->getMessage(), 1, $caught);
        }
    }

    /**
     * Event identifier
     *
     * @return EventId
     */
    public function eventId(): EventId
    {
        return $this->eventId;
    }

    /**
     * Date and time event has occurred
     *
     * @return DateTimeImmutable
     */
    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }

    /**
     * The aggregate identifier of the entity responsible for this event
     *
     * @return null|AggregateRootIdentifier
     */
    public function author(): ?AggregateRootIdentifier
    {
        return $this->author;
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @return mixed data which can be serialized by json_encode(),
     *               which is a value of any type other than a resource.
     */
    abstract public function jsonSerialize();
}
