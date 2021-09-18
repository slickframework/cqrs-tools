<?php

/**
 * This file is part of slick/cqrs-tools
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\CQRSTools\Domain;

use Exception;
use Ramsey\Uuid\Uuid;

/**
 * GenericRootIdentifier
 *
 * @package Slick\CQRSTools\Domain
*/
class GenericRootIdentifier extends AggregateRootIdentifier
{
    /**
     * @var string|null
     */
    private $uuidStr;

    /**
     * Creates a GenericRootIdentifier
     *
     * @param string|null $uuidStr
     *
     * @throws Exception
     */
    public function __construct(string $uuidStr = null)
    {
        $this->uuidStr = $uuidStr;
        if (Uuid::isValid($uuidStr)) {
                parent::__construct($uuidStr);
                return;
        }

        parent::__construct();
    }

    /**
     * __toString
     *
     * @return string
     */
    public function __toString()
    {
        return $this->uuidStr ?: parent::__toString();
    }

    /**
     * equalsTo
     *
     * @param mixed $other
     * @return bool
     */
    public function equalsTo($other): bool
    {
        if (!($other instanceof AggregateRootIdentifier)) {
            return false;
        }

        if ($other instanceof GenericRootIdentifier) {
            return $other->uuidStr === $this->uuidStr;
        }

        return parent::equalsTo($other);
    }

    /**
     * jsonSerialize
     *
     * @return string
     */
    public function jsonSerialize(): string
    {
        return (string) $this;
    }
}
