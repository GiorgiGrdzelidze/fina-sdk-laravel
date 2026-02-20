<?php

declare(strict_types=1);

/**
 * Date formatting utilities for the FINA Web API.
 */

namespace Fina\Sdk\Laravel\Support;

use DateTimeInterface;

/**
 * Formats dates into the string format expected by the FINA Web API.
 *
 * FINA requires dates as `yyyy-MM-ddTHH:mm:ss` (no timezone suffix).
 */
final class FinaDate
{
    /**
     * Format a date for the FINA API.
     *
     * @param  DateTimeInterface  $dt  Any date/time object (Carbon, DateTimeImmutable, etc.).
     * @return string Formatted as `Y-m-d\TH:i:s`.
     */
    public static function toFina(DateTimeInterface $dt): string
    {
        return $dt->format('Y-m-d\TH:i:s');
    }

    /**
     * Alias for {@see toFina()} — semantic name used by Reporting endpoints.
     *
     * @param  DateTimeInterface  $dt  Any date/time object.
     * @return string Formatted as `Y-m-d\TH:i:s`.
     */
    public static function toFinaDateTime(DateTimeInterface $dt): string
    {
        return self::toFina($dt);
    }

    /**
     * Pass-through for values already in the correct FINA date format.
     *
     * @param  string  $value  A pre-formatted date string.
     * @return string The same string, unchanged.
     */
    public static function raw(string $value): string
    {
        return $value;
    }
}
