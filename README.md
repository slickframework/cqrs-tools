# Slick DDD/CQRS/Event Sourcing

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

`Slick/CQRSTools` is an useful library for Event Souring style applications. It has a collection of tools that can speed up
development using Domain Driven Development and CQRS techniques.

This package is compliant with PSR-2 code standards and PSR-4 autoload standards. It
also applies the [semantic version 2.0.0](http://semver.org) specification.

## Install

Via Composer

``` bash
$ composer require slick/cqrs-tools
```

## Usage
### Creating events
Events are a simple and descriptive way of recording some action that was done. They usually have some
metadata and the values that were used to change the domain.
Consider the event of creating a user:
```php
<?php

use Ramsey\Uuid\Uuid;
use Slick\CQRSTools\Event\AbstractEvent;
use Slick\CQRSTools\Event;

class UserWasCreated extends AbstractEvent implements Event
{
    /** @var Uuid */
    private $userId;
    
    /** @var string */
    private $name;
    
    public function __construct(Uuid $userId, string  $name)
    {
        parent::__construct();
        $this->userId = $userId;
        $this->name = $name;
    }
    
    public function jsonSerialize()
    {
        return [
            'userId' => $this->userId,
            'name' => $this->name    
        ];
    }
    
    public function unserializeEvent($data): void
    {
        $this->userId = Uuid::fromString($data->userId);
        $this->name = $data->name;
    }
    
}
```
The above class defines an `UserWasCreated` event and wraps the ID and name of the crated user. There are
3 more fields, consider metadata, for every event that extends the `AbstractEvent` abstract class: `EventId`, `Author`
and `OccurredOn` witch holds the unique event ID, an optional author (user, service, etc...) ID and the date
and time the event has occurred. 
Note that you need to implement a `jsonSerialize()` and `unserializeEvent()`. Those methods are used
to publish the event to other services like a database or a message queue and to unserialize the event
from those services.
The metadata fields are serialized and unserialized automatically and you don't need to include them in those
methods.  


### Recording events with generators
All changes to your domain should generate an event. Therefore a simple way of achieving this is by adding
such behavior to your domain objects.
Consider the following `User` object:
```php
<?php

use Ramsey\Uuid\Uuid;

final class User
{
    /** @var Uuid */
    private $userId;
    
    /** @var string */
    private $name;
    
    public function __construct(string $name) {
        $this->name = $name;
        $this->userId = Uuid::uuid4();
    }
    
    public function userId(): Uuid
    {
        return $this->userId;
    }
    
    public function name(): string
    {
        return $this->name;
    }
}
```
This is a very simple implementation of a domain `User`. Lets consider that every time we create an
user an `UserWasCreated` event should be created:

```php
<?php

use Ramsey\Uuid\Uuid;
use Slick\CQRSTools\Event\EventGenerator;
use Slick\CQRSTools\Event\EventGeneratorMethods;

final class User implements EventGenerator
{
    // ... other code
    
    use EventGeneratorMethods;
    
    public function __construct(string $name) {
        $this->name = $name;
        $this->userId = Uuid::uuid4();
        // Create the event
        $this->recordThat(new UserWasCreated($this->userId, $name));
    }
    
}

```
// Work in progress...

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email slick.framework@gmail.com instead of using the issue tracker.

## Credits

- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

[ico-version]: https://img.shields.io/packagist/v/slick/cqrs-tools.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/slickframework/cqrs-tools/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/slickframework/cqrs-tools.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/slickframework/cqrs-tools.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/slick/cqrs-tools.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/slick/cqrs-tools
[link-travis]: https://travis-ci.org/slickframework/cqrs-tools
[link-scrutinizer]: https://scrutinizer-ci.com/g/slickframework/cqrs-tools/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/slickframework/cqrs-tools
[link-downloads]: https://packagist.org/packages/slickframework/cqrs-tools
[link-contributors]: https://github.com/slickframework/cqrs-tools/graphs/contributors
