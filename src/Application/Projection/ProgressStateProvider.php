<?php

/**
 * This file is part of CQRS-Tools package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Slick\CQRSTools\Application\Projection;

/**
 * ProgressStateProvider
 *
 * @package Slick\CQRSTools\Application\Projection
 */
interface ProgressStateProvider
{

    /**
     * Registers a projectionist state listener
     *
     * @param $listener
     *
     * @return ProgressStateProvider
     */
    public function register($listener): ProgressStateProvider;
}
