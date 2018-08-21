<?php

/**
 * This file is part of slick/cqrs-tools
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\CQRSTools\Domain\Common;

/**
 * Stringable
 *
 * @package Slick\CQRSTools\Domain\Common
 */
interface Stringable
{

    /**
     * String representation of the object
     *
     * @return string
     */
    public function __toString();
}
