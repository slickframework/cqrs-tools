<?php

/**
 * This file is part of slick/cqrs-tools
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\CQRSTools\Application\Projection;

/**
 * Projector
 *
 * @package Slick\CQRSTools\Application\Projection
 */
interface Projector
{

    const RUN_FROM_BEGINNING = 0;
    const RUN_FROM_NOW       = 5;
    const RUN_ONCE           = 10;

    /**
     * Starting point for this projector
     *
     * @return int
     */
    public function runFrom(): int;

    /**
     * Clear all projection data
     */
    public function retire(): void;
}
