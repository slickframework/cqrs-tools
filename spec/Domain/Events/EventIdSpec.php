<?php

/**
 * This file is part of slick/cqrs-tools
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\CQRSTools\Domain\Events;

use JsonSerializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Slick\CQRSTools\Domain\Common\Comparable;
use Slick\CQRSTools\Domain\Common\Stringable;
use Slick\CQRSTools\Domain\Events\EventId;
use PhpSpec\ObjectBehavior;
use Slick\CQRSTools\Domain\Exception\IdentifierCreationException;

/**
 * EventIdSpec specs
 *
 * @package spec\Slick\CQRSTools\Domain\Events
 */
class EventIdSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(EventId::class);
    }

    function it_wraps_an_uuid()
    {
        $this->wrappedUuid()->shouldBeAnInstanceOf(UuidInterface::class);
    }

    function it_can_be_created_from_a_string()
    {
        $str = Uuid::uuid4();
        $this->beConstructedWith((string) $str);
        $this->wrappedUuid()->equals($str)->shouldBe(true);
    }

    function it_throws_an_Exception_for_invalid_uuid()
    {
        $this->beConstructedWith('test');
        $this->shouldThrow(IdentifierCreationException::class)
            ->duringInstantiation();
    }

    function it_can_be_converted_to_string()
    {
        $str = Uuid::uuid4();
        $this->beConstructedWith((string) $str);
        $this->shouldBeAnInstanceOf(Stringable::class);
        $this->__toString()->shouldBe((string) $str);
    }

    function it_can_be_compared_to_other_object()
    {
        $this->shouldBeAnInstanceOf(Comparable::class);
    }

    function it_can_be_concerted_to_json()
    {
        $str = Uuid::uuid4();
        $this->beConstructedWith((string) $str);
        $this->shouldBeAnInstanceOf(JsonSerializable::class);
        $this->jsonSerialize()->shouldBe((string) $str);
    }
}
