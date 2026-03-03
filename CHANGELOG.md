# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.3.0] - 2026-01-05

### Changed
- **BREAKING**: Replaced PSR-4 autoload override mechanism with Symfony CompilerPass
- Simplified installation process - no longer requires `make:hexagonal:autoload` command
- Improved bundle architecture using proper Symfony decorator pattern

### Added
- `HexagonalMakerCompilerPass` to decorate Symfony MakerBundle's Generator service
- `HexagonalGenerator` class that properly decorates the original Generator
- Comprehensive tests for CompilerPass and Generator
- Better documentation with clearer installation instructions

### Removed
- `make:hexagonal:autoload` command (no longer needed)
- `HexagonalMakerAutoload` class
- `bin/edit-autoload.sh` script
- `HexagonalMakerAutoloadTest` test class

### Fixed
- Improved maintainability and compatibility with future Symfony versions
- Cleaner separation of concerns between bundle and Symfony MakerBundle

## [1.2.0] - Previous versions

- `make:hexagonal:usecase` command for generating use cases
- Support for Request, Response, UseCase, and PresenterInterface generation
- Interactive field wizard for Request properties
- Basic configuration for architecture layer paths
