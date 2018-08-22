<?php

/**
 * This file is part of slick/cqrs-tools
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\CQRSTools\Domain\Projection;

use Slick\CQRSTools\Application\Projection\Projector;

/**
 * ProjectorStateLedger
 *
 * @package Slick\CQRSTools\Domain\Projection
 */
interface ProjectorStateLedger
{

    /**
     * Gets the state for provided projector
     *
     * This method MUST create a new projector state entry when there are no entries
     * for the provided projector.
     *
     * @param Projector $projector
     *
     * @return ProjectorState
     */
    public function get(Projector $projector): ProjectorState;

    /**
     * Update the changes on the provided projector state
     *
     * @param ProjectorState $projectorState
     *
     * @return ProjectorState
     */
    public function update(ProjectorState $projectorState): ProjectorState;

    /**
     * Removes a information for the a retired projector
     *
     * @param ProjectorState $projectorState
     */
    public function remove(ProjectorState $projectorState): void;
}
