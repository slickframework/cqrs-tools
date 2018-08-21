<?php

/**
 * This file is part of slick/cqrs-tools
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\CQRSTools\Domain\Event;

use ReflectionClass;
use Slick\CQRSTools\Event;
use Slick\CQRSTools\Event\AbstractEvent;

/**
 * StoredEvent
 *
 * @package Slick\CQRSTools\Domain\Event
*/
class StoredEvent extends AbstractEvent implements Event
{

    /**
     * @var string
     */
    private $eventClassName;

    /**
     * @var null|mixed
     */
    private $data = null;

    /**
     * Creates a stored event from other event
     *
     * @param Event $event
     *
     * @return StoredEvent
     */
    public static function createFromEvent(Event $event): StoredEvent
    {
        if ($event instanceof StoredEvent) {
            return $event;
        }

        $stored = new StoredEvent($event->author());
        $stored->eventId = $event->eventId();
        $stored->occurredOn = $event->occurredOn();
        $stored->eventClassName = get_class($event);
        $stored->data = json_encode($event);

        return $stored;
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @return mixed data which can be serialized by json_encode(),
     *               which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return [
            'eventId' => (string) $this->eventId,
            'occurredOn' => $this->occurredOn->format('Y-m-d H:i:s.u'),
            'author' => (string) $this->author,
            'eventClassName' => $this->eventClassName,
            'data' => json_decode($this->data)
        ];
    }

    /**
     * Original event FQ Class name
     *
     * @return string
     */
    public function eventClassName(): string
    {
        return $this->eventClassName;
    }

    /**
     * Data from event serialization
     *
     * @return mixed|null
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return Event
     * @throws \ReflectionException
     */
    public function event(): Event
    {
        $reflection = new ReflectionClass($this->eventClassName);
        /** @var Event $event */
        $event = $reflection->newInstanceWithoutConstructor();

        $properties = [
            'author' => $this->author, 'occurredOn' => $this->occurredOn, 'eventId' => $this->eventId
        ];
        $event = $this->assignProperties($reflection, $properties, $event);
        $event->unserializeEvent(json_decode($this->data));

        return $event;
    }

    private function assignProperties(ReflectionClass $reflection, array $properties, Event $event): Event
    {
        foreach ($properties as $name => $value) {
            if (!$reflection->hasProperty($name)) {
                continue;
            }

            $property = $reflection->getProperty($name);
            $property->setAccessible(true);
            $property->setValue($event, $value);
            $property->setAccessible(false);
        }

        return $event;
    }

    /**
     * Used to unserialize from a stored event
     *
     * @param mixed $data
     */
    public function unserializeEvent($data): void
    {
        // do nothing!
    }
}
