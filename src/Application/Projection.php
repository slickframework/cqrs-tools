<?php

/**
 * This file is part of slick/cqrs-tools
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\CQRSTools\Application;

/**
 * Projection
 *
 * @package Slick\CQRSTools\Application
 */
interface Projection
{
    /**
     * Clears the projection
     */
    public function retire(): void;
}
