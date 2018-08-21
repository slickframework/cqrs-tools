<?php

/**
 * This file is part of slick/cqrs-tools
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\CQRSTools\Domain\Event;

use League\Event\EventInterface;
use Slick\CQRSTools\Event;
use Slick\CQRSTools\Event\AbstractListener;
use Slick\CQRSTools\Event\EventListener;

/**
 * EventStoreListener
 *
 * @package Slick\CQRSTools\Domain\Event
*/
final class EventStoreListener extends AbstractListener implements EventListener
{
    /**
     * @var EventStore
     */
    private $eventStore;

    /**
     * Creates an Event Store Listener
     *
     * @param EventStore $eventStore
     */
    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    /**
     * Handle an event.
     *
     * @param EventInterface|Event $event
     *
     * @return void
     */
    public function handle(EventInterface $event)
    {
        $this->eventStore->append(StoredEvent::createFromEvent($event));
    }
}
