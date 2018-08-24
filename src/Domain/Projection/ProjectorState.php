<?php

/**
 * This file is part of slick/cqrs-tools
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\CQRSTools\Domain\Projection;

use JsonSerializable;
use Slick\CQRSTools\Application\Projection\Projector;
use Slick\CQRSTools\Domain\Event\EventId;
use Slick\CQRSTools\Event;

/**
 * ProjectorState
 *
 * @package Slick\CQRSTools\Domain\Projection
*/
final class ProjectorState implements JsonSerializable
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var EventId
     */
    private $lastEvent;

    /**
     * @var bool
     */
    private $halted = true;

    /**
     * @var bool
     */
    private $projecting = false;

    /**
     * @var int
     */
    private $projectorRunsFrom;

    /**
     * @var null|string
     */
    private $reason = null;

    /**
     * Creates a Projector State record
     *
     * @param Projector $projector
     */
    public function __construct(Projector $projector)
    {
        $this->name = get_class($projector);
        $this->projectorRunsFrom = $projector->runFrom();
    }

    /**
     * Projector name/identifier
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Last played event
     *
     * @return null|EventId
     */
    public function lastEvent(): ?EventId
    {
        return $this->lastEvent;
    }

    /**
     * Registers last played event
     *
     * @param Event $event
     */
    public function lastEventWas(Event $event): void
    {
        $this->lastEvent = $event->eventId();
        $this->halted = false;
        $this->reason = null;
    }

    /**
     * Projector halt state
     *
     * @return bool
     */
    public function isHalted(): bool
    {
        return $this->halted;
    }

    /**
     * Halts the projector
     *
     * @param string $reason The reason why projector should be halted
     */
    public function halt(string $reason): void
    {
        $this->halted = true;
        $this->reason = $reason;
        $this->projecting = false;
    }

    /**
     * Reports whether or not this projector is projecting an event stream
     *
     * @return bool
     */
    public function isProjecting(): bool
    {
        return $this->projecting;
    }

    /**
     * Sets projecting state as started
     */
    public function startProjecting(): void
    {
        $this->projecting = true;
    }

    /**
     * Sets projecting state as stopped
     */
    public function stopProjecting(): void
    {
        $this->projecting = false;
    }

    /**
     * Projector run mode
     *
     * @return int
     */
    public function projectorRunsFrom(): int
    {
        return $this->projectorRunsFrom;
    }

    /**
     * Reason why the projector was halted
     *
     * @return string|null
     */
    public function whyIsHalted(): ?string
    {
        return $this->reason;
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
            'name' => $this->name,
            'projectorRunsFrom' => $this->projectorRunsFrom,
            'lastEvent' => $this->lastEvent,
            'projecting' => $this->projecting,
            'halted' => $this->halted,
            'reason' => $this->reason
        ];
    }
}
