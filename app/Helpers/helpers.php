<?php

use App\Helpers\ColorHelper;

if (!function_exists('hex_to_rgb')) {
    /**
     * Convert hex color to RGB format
     *
     * @param string $hex
     * @return string
     */
    function hex_to_rgb($hex)
    {
        return ColorHelper::hexToRgb($hex);
    }
}