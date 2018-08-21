<?php

/**
 * This file is part of slick/cqrs-tools
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\CQRSTools\Domain\Common;

/**
 * Comparable
 *
 * @package Slick\CQRSTools\Domain\Common
 */
interface Comparable
{

    /**
     * Compares object for equality
     *
     * @param mixed $other
     *
     * @return bool
     */
    public function equalsTo($other): bool;
}
