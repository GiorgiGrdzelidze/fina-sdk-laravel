<?php

declare(strict_types=1);

/**
 * Exception for missing or invalid FINA SDK configuration.
 */

namespace Fina\Sdk\Laravel\Exceptions;

/**
 * Thrown when required FINA SDK configuration is missing or invalid.
 *
 * Common causes: missing FINA_BASE_URL, FINA_LOGIN, or FINA_PASSWORD in .env.
 */
final class FinaConfigException extends FinaException {}
