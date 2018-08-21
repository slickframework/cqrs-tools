<?php

/**
 * This file is part of slick/cqrs-tools
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\CQRSTools\Domain;

use Exception;
use JsonSerializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Slick\CQRSTools\Domain\Common\Comparable;
use Slick\CQRSTools\Domain\Common\Stringable;
use Slick\CQRSTools\Domain\Exception\IdentifierCreationException;

/**
 * Aggregate Root Identifier
 *
 * @package Slick\CQRSTools\Domain
 */
abstract class AggregateRootIdentifier implements Stringable, Comparable, JsonSerializable
{

    /**
     * @var UuidInterface
     */
    protected $uuid;

    /**
     * Creates an AggregateRootIdentifier
     *
     * @param string|null $uuidStr
     *
     * @throws IdentifierCreationException when internal UUID cannot be created
     */
    public function __construct(string $uuidStr = null)
    {
        try {
            $this->uuid = Uuid::uuid4();
            if ($uuidStr) {
                $this->uuid = $this->createFromString($uuidStr);
            }
        } catch (Exception $caught) {
            throw new IdentifierCreationException($caught->getMessage(), 1, $caught);
        }
    }

    /**
     * Internal UUID
     *
     * @return UuidInterface
     */
    public function wrappedUuid(): UuidInterface
    {
        return $this->uuid;
    }

    /**
     * Compares object for equality
     *
     * @param mixed $other
     *
     * @return bool
     */
    public function equalsTo($other): bool
    {
        $sameType = is_object($other) && is_a($other, get_called_class());
        return $sameType && $this->uuid->equals($other);
    }

    /**
     * String version of UUID that will be converted to a JSON string
     *
     * @return mixed data which can be serialized by json_encode(),
     *               which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return (string) $this->uuid;
    }

    /**
     * String representation of the object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->uuid;
    }

    /**
     * Creates an UUID with provided UUID string
     *
     * @param string $uuidStr
     *
     * @return UuidInterface
     *
     * @throws IdentifierCreationException If the provided text is not a valid UUID string
     */
    protected function createFromString(string $uuidStr): UuidInterface
    {
        if (!Uuid::isValid($uuidStr)) {
            throw new IdentifierCreationException(
                "Invalid aggregate root ID '{$uuidStr}'."
            );
        }

        return Uuid::fromString($uuidStr);
    }
}
