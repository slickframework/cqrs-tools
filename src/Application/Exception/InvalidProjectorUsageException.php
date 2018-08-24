<?php

/**
 * This file is part of slick/cqrs-tools
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\CQRSTools\Application\Exception;

use InvalidArgumentException;
use Slick\CQRSTools\Exception;

/**
 * Invalid Projector Usage Exception
 *
 * @package Slick\CQRSTools\Application\Exception
 */
class InvalidProjectorUsageException extends InvalidArgumentException implements Exception
{

}
