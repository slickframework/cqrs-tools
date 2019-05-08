<?php

/**
 * This file is part of CQRS-Tools package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Slick\CQRSTools\Application\Projection;

/**
 * ProgressStateProviderMethods
 *
 * @package Slick\CQRSTools\Application\Projection
 */
trait ProgressStateProviderMethods
{
    /** @var ProgressStateListener[]  */
    protected $listeners = [];

    /**
     * Registers a projectionist state listener
     *
     * @param $listener
     *
     * @return ProgressStateProvider|self|$this
     */
    public function register($listener): ProgressStateProvider
    {
        array_push($this->listeners, $listener);
        return $this;
    }

    /**
     * Notify listeners of progress max steps
     *
     * @param int $max
     */
    protected function notifyMaxSteps(int $max): void
    {
        foreach ($this->listeners as $listener) {
            $listener->setMaxSteps($max);
        }
    }

    /**
     * Notify listeners of progress advanced steps
     *
     * @param int $steps
     */
    protected function notifyAdvance(int $steps = 1): void
    {
        foreach ($this->listeners as $listener) {
            $listener->advance($steps);
        }
    }

    /**
     * Notify listeners of progress finish
     */
    protected function notifyFinish(): void
    {
        foreach ($this->listeners as $listener) {
            $listener->finish();
        }
    }

    /**
     * Notify listeners of handled vents
     *
     * @param int $processed
     */
    protected function notifyHandledEvent(int $processed = 1): void
    {
        foreach ($this->listeners as $listener) {
            $listener->addProcessedEvents($processed);
        }
    }
}
