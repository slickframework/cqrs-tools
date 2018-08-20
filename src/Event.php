<?php

/**
 * This file is part of slick/cqrs-tools
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\CQRSTools;

use DateTimeImmutable;

/**
 * Interface Event
 *
 * @package Slick\CQRSTools
 */
interface Event
{

    /**
     * Date and time event has occurred
     *
     * @return DateTimeImmutable
     */
    public function occurredOn(): DateTimeImmutable;
}
