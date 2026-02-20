# Contributing to FINA SDK for Laravel

Thank you for considering a contribution! This document outlines the process and standards.

## Getting Started

1. **Fork** the repository and clone your fork
2. Install dependencies: `composer install`
3. Create a feature branch: `git checkout -b feature/your-feature`

## Development Workflow

### Code Style

This project uses [Laravel Pint](https://laravel.com/docs/pint) with the default Laravel preset.

```bash
# Check style
composer pint -- --test

# Auto-fix style
composer pint
```

### Static Analysis

[PHPStan](https://phpstan.org/) level 6 is enforced.

```bash
composer phpstan -- --memory-limit=512M
```

### Tests

All tests must use `Http::fake()`. No real HTTP requests, no database, no external services.

```bash
composer test
```

### Full CI Check

Run all three checks before submitting:

```bash
composer ci
```

## Pull Request Guidelines

1. **One concern per PR** — keep changes focused
2. **Write tests** for new functionality (`Http::fake` only, no DB)
3. **Run `composer ci`** and ensure it passes
4. **Do not change** FINA endpoint URLs, HTTP verbs, or payload shapes without prior discussion in an issue
5. **Follow existing code style** — Pint enforces this automatically
6. **Update documentation** if your change affects public API or configuration

## What We Accept

- Bug fixes with regression tests
- New FINA API endpoint coverage
- Improved DTO typing or validation rules
- Documentation improvements
- Test coverage improvements

## What We Do Not Accept

- Changes that require a database or migrations
- Host-application routes or controllers
- Dependencies on packages outside Laravel's core ecosystem
- Breaking changes to the public API without a major version bump

## Reporting Bugs

Please include:

- PHP and Laravel version
- FINA Web API version
- Minimal reproduction steps
- Expected vs actual behaviour

## Security Vulnerabilities

Please see [SECURITY.md](SECURITY.md) for responsible disclosure instructions.
