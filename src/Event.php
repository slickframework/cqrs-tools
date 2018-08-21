<?php

/**
 * This file is part of slick/cqrs-tools
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\CQRSTools;

use DateTimeImmutable;
use JsonSerializable;
use League\Event\EventInterface;
use Slick\CQRSTools\Domain\AggregateRootIdentifier;
use Slick\CQRSTools\Domain\Events\EventId;

/**
 * Interface Event
 *
 * @package Slick\CQRSTools
 */
interface Event extends EventInterface, JsonSerializable
{
    /**
     * Event identifier
     *
     * @return EventId
     */
    public function eventId(): EventId;

    /**
     * Date and time event has occurred
     *
     * @return DateTimeImmutable
     */
    public function occurredOn(): DateTimeImmutable;

    /**
     * The aggregate identifier of the entity responsible for this event
     *
     * @return null|AggregateRootIdentifier
     */
    public function author(): ?AggregateRootIdentifier;
}
