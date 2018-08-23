<?php

/**
 * This file is part of slick/cqrs-tools
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\CQRSTools\Application\Projection;

use League\Event\EventInterface;
use Slick\CQRSTools\Application\Projection\ProjectionEventListener\ListenerEventStore;
use Slick\CQRSTools\Domain\Event\StoredEvent;
use Slick\CQRSTools\Domain\Projection\ProjectorStateLedger;
use Slick\CQRSTools\Event;
use Slick\CQRSTools\Event\AbstractListener;
use Slick\CQRSTools\Event\EventListener;

/**
 * ProjectionEventListener
 *
 * @package Slick\CQRSTools\Application\Projection
*/
final class ProjectionEventListener extends AbstractListener implements EventListener
{
    /**
     * @var ListenerEventStore
     */
    private $eventStore;

    /**
     * @var array
     */
    private $projectors;

    /**
     * @var EventHandlingStrategy
     */
    private $strategy;

    /**
     * @var ProjectorStateLedger
     */
    private $ledger;

    /**
     * @var Projectionist
     */
    private $projectionist;

    /**
     * Creates a Projection Event Listener
     *
     * @param array                 $projectors
     * @param EventHandlingStrategy $strategy
     * @param ProjectorStateLedger  $ledger
     */
    public function __construct(array $projectors, EventHandlingStrategy $strategy, ProjectorStateLedger $ledger)
    {
        $this->projectors = $projectors;
        $this->strategy = $strategy;
        $this->ledger = $ledger;

        $this->eventStore = new ListenerEventStore();
        $this->projectionist = new Projectionist($this->eventStore, $ledger, $strategy);
    }

    /**
     * Handle an event.
     *
     * @param EventInterface|Event $event
     *
     * @return void
     * @throws \Exception
     */
    public function handle(EventInterface $event)
    {
        $this->eventStore->append(StoredEvent::createFromEvent($event));
        $this->projectionist->play($this->projectors);
    }
}
