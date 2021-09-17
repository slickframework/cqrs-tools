<?php

/**
 * This file is part of slick/cqrs-tools
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\CQRSTools\Domain\Event;

use Slick\CQRSTools\Domain\Event\EventId;
use Slick\CQRSTools\Domain\Event\StoredEvent;
use PhpSpec\ObjectBehavior;
use Slick\CQRSTools\Domain\GenericRootIdentifier;
use Slick\CQRSTools\Event;

/**
 * StoredEventSpec specs
 *
 * @package spec\Slick\CQRSTools\Domain\Event
 */
class StoredEventSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(StoredEvent::class);
    }

    function its_an_event()
    {
        $this->shouldBeAnInstanceOf(Event::class);
        $this->shouldHaveType(Event\AbstractEvent::class);
    }

    /**
     * @param \PhpSpec\Wrapper\Collaborator|Event $event
     * @throws \Exception
     */
    function it_can_be_created_from_other_event()
    {
        $author = new EventId();
        $event = new AcmeEvent($author);

        $this->beConstructedThrough('createFromEvent', [$event]);

        $this->eventId()->shouldBe($event->eventId());
        $this->occurredOn()->shouldBe($event->occurredOn());
        $this->author()->shouldBe($author);
        $this->eventClassName()->shouldBe(get_class($event));
        $this->data()->shouldBe(json_encode($event));
    }

    /**
     * @throws \ReflectionException
     */
    function it_can_reconstruct_original_event()
    {
        $event = new AcmeEvent();
        $this->beConstructedThrough('createFromEvent', [$event]);

        $other = $this->event();
        $other->shouldBeAnInstanceOf(AcmeEvent::class);
        $other->shouldNotBe($event);

        $other->occurredOn()->format('Y-m-d H:i:d')->shouldBe($event->occurredOn()->format('Y-m-d H:i:d'));
        $other->eventId()->equalsTo($event->eventId())->shouldBe(true);
        $other->foo()->shouldBe('bar');
    }

    function it_can_be_converted_to_json()
    {
        $event = new AcmeEvent();
        $this->beConstructedThrough('createFromEvent', [$event]);

        $data = $this->jsonSerialize();
        $data->shouldHaveKeyWithValue('eventId', (string) $event->eventId());
        $data->shouldHaveKeyWithValue('occurredOn', $event->occurredOn()->format('Y-m-d H:i:s.u'));
        $data->shouldHaveKeyWithValue('author', (string) $event->author());
        $data->shouldHaveKeyWithValue('eventClassName', get_class($event));
        $data->shouldHaveKey('data');
    }
}

class AcmeEvent extends Event\AbstractEvent implements Event
{

    private $foo = 'bar';

    /**
     * @return string
     */
    public function foo(): string
    {
        return $this->foo;
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @return mixed data which can be serialized by json_encode(),
     *               which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return ['foo' => 'bar'];
    }

    /**
     * Used to unserialize from a stored event
     *
     * @param mixed $data
     */
    public function unserializeEvent($data): void
    {
        $this->foo = $data->foo;
    }
}