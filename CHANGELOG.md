# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [v0.5.0] 2019-05-08
### Added
- `ProgressStateProvider` interface to enable projectionist progress state notifications
- `ProgressStateListener` interface that specifies the projectionist state changes listener
### Fixed
- Type hint bug on projectionist

## [v0.4.0] 2019-05-07
### Added
- Event stream interface to allow other stream implementations
### Change
- `EventStream` to implement `Domain\Event\Stream` interface

## [v0.3.0] 2018-08-24
### Added
- `Projectionist` to play, boot and retire projectors
- `ProjectorStateLedger` that tracks projector state information
- `Projection`, `Projector` and `EventHandlingStrategy` interfaces
- `EventHandlingListener` projectionist

## [v0.2.0] 2018-08-21
### Added
- `Event` interface
- Event generation and publishing system
- `EventStore` and `EventStoreListener`
- StoredEvent serialization
- Common domain tools: `Stringable` and `Comparable` interfaces
- `AggregateRootIdentifier` abstract class

## [v0.1.0] 2018-08-20
### Added
- Basic documentation and static project files
- Initial composer definitions.
- PHPSpec bootstrap

[Unreleased]: https://github.com/slickframework/cqrs-tools/compare/v0.5.0...HEAD
[v0.5.0]: https://github.com/slickframework/cqrs-tools/compare/v0.4.0...v0.5.0
[v0.4.0]: https://github.com/slickframework/cqrs-tools/compare/v0.3.0...v0.4.0
[v0.3.0]: https://github.com/slickframework/cqrs-tools/compare/v0.2.0...v0.3.0
[v0.2.0]: https://github.com/slickframework/cqrs-tools/compare/v0.1.0...v0.2.0
[v0.1.0]: https://github.com/slickframework/cqrs-tools/compare/85b339f...v0.1.0
