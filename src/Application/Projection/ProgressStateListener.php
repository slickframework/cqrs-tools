<?php

/**
 * This file is part of CQRS-Tools package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\CQRSTools\Application\Projection;

/**
 * ProgressStateListener
 *
 * @package Slick\CQRSTools\Application\Projection
 */
interface ProgressStateListener
{

    /**
     * Set the max steps the progress will perform
     *
     * @param int $max
     */
    public function setMaxSteps(int $max): void;

    /**
     * Advance the progress by X steps
     *
     * @param int $steps
     */
    public function advance(int $steps = 1): void;

    /**
     * Returns the number of processed events
     *
     * @return int
     */
    public function processedEvents(): int;

    /**
     * Updates the number of events that where used by projector
     *
     * This will add the provided processed events to the total list of events
     * kept by listener.
     *
     * @param int $processed
     */
    public function addProcessedEvents(int $processed = 1): void;

    /**
     * Tells listeners that projectionist has finished processing events
     */
    public function finish(): void;
}
