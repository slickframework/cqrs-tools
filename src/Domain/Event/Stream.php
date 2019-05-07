<?php

/**
 * This file is part of CQRS-Tools package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\CQRSTools\Domain\Event;

use Countable;
use Iterator;

/**
 * Stream
 *
 * @package Slick\CQRSTools\Domain\Event
 */
interface Stream extends Iterator, Countable
{

}
