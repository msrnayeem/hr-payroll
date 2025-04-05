<?php

use Carbon\Carbon;

if (!function_exists('format12hr')) {
    /**
     * Formats a time string (HH:MM:SS or HH:MM) into AM/PM format (h:i A).
     *
     * @param  string|null  $timeString The time string to format.
     * @return string|null The formatted time in AM/PM, or null if the input is invalid.
     */
    function format12hr(?string $timeString): ?string
    {
        if (!$timeString) {
            return null;
        }

        try {
            return Carbon::parse($timeString)->format('h:i A');
        } catch (\Exception $e) {
            // Handle cases where the time string might be in an unexpected format
            return null; // Or you could log the error for debugging
        }
    }
}

if (!function_exists('format24hr')) {
    /**
     * Formats a time string (HH:MM:SS or HH:MM) into 24-hour format (H:i).
     *
     * @param  string|null  $timeString The time string to format.
     * @return string|null The formatted time in 24-hour format, or null if the input is invalid.
     */
    function format24hr(?string $timeString): ?string
    {
        if (!$timeString) {
            return null;
        }

        try {
            return Carbon::parse($timeString)->format('H:i');
        } catch (\Exception $e) {
            // Handle cases where the time string might be in an unexpected format
            return null; // Or you could log the error for debugging
        }
    }
}
