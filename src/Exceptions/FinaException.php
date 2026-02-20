<?php

declare(strict_types=1);

/**
 * Base exception class for all FINA SDK errors.
 */

namespace Fina\Sdk\Laravel\Exceptions;

use RuntimeException;

/**
 * Base exception for all FINA SDK related errors.
 *
 * Catch this type to handle any error originating from the SDK,
 * including configuration, HTTP, remote API, and validation errors.
 */
class FinaException extends RuntimeException {}
