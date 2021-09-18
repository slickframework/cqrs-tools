<?php

/**
 * This file is part of slick/cqrs-tools
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\CQRSTools\Domain;

use Ramsey\Uuid\Uuid;
use Slick\CQRSTools\Domain\AggregateRootIdentifier;
use Slick\CQRSTools\Domain\GenericRootIdentifier;
use PhpSpec\ObjectBehavior;

/**
 * GenericRootIdentifierSpec specs
 *
 * @package spec\Slick\CQRSTools\Domain
 */
class GenericRootIdentifierSpec extends ObjectBehavior
{

    private $uuidStr;

    function let()
    {
        $this->uuidStr = (string)Uuid::uuid4();
        $this->beConstructedWith($this->uuidStr);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(GenericRootIdentifier::class);
    }

    function its_an_aggregate_root_identifier()
    {
        $this->shouldBeAnInstanceOf(AggregateRootIdentifier::class);
    }

    function it_wraps_an_uuid()
    {
        $this->wrappedUuid()->__toString()->shouldBe($this->uuidStr);
    }

    function it_can_be_created_with_any_string()
    {
        $id = '2000010000';
        $this->beConstructedWith($id);
        $this->__toString()->shouldBe($id);
        $this->equalsTo(new GenericRootIdentifier($id))->shouldBe(true);
        $this->equalsTo($id)->shouldBe(false);
        $this->equalsTo(new GenericRootIdentifier())->shouldBe(false);
        $this->jsonSerialize()->shouldBe($id);
    }
}
