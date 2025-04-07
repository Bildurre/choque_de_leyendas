<?php

namespace App\Helpers;

class ColorHelper
{
    /**
     * Convert hex color to RGB format
     *
     * @param string $hex
     * @return string
     */
    public static function hexToRgb($hex)
    {
        // Remove # if present
        $hex = ltrim($hex, '#');
        
        // Parse the hex string
        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        
        return "$r, $g, $b";
    }
}