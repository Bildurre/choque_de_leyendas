<?php

if (!function_exists('locale_name')) {
    /**
     * Get the localized name of a locale code
     *
     * @param string|null $locale The locale code (defaults to current locale)
     * @return string The localized name of the locale
     */
    function locale_name(string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        $supportedLocales = config('laravellocalization.supportedLocales');
        
        return $supportedLocales[$locale]['native'] ?? $locale;
    }
}

if (!function_exists('pdf_asset_to_path')) {
    /**
     * Convert asset URL to file path for PDF generation
     *
     * @param string $url The asset URL
     * @return string The file path
     */
    function pdf_asset_to_path(string $url): string
    {
        $url = parse_url($url, PHP_URL_PATH);
        if (strpos($url, '/storage/') === 0) {
            return storage_path('app/public/' . substr($url, 9));
        }
        if (strpos($url, 'storage/') === 0) {
            return storage_path('app/public/' . substr($url, 8));
        }
        return public_path($url);
    }
}

if (!function_exists('image_to_base64')) {
    /**
     * Convert image file to base64 data URI
     *
     * @param string $path The file path
     * @param string|null $mimeType Optional mime type override
     * @return string|null The base64 data URI or null if file doesn't exist
     */
    function image_to_base64(string $path, ?string $mimeType = null): ?string
    {
        if (file_exists($path)) {
            $imageData = file_get_contents($path);
            
            // Determine mime type
            if (!$mimeType) {
                $mimeType = mime_content_type($path);
                
                // Fix for SVG mime type
                if (pathinfo($path, PATHINFO_EXTENSION) === 'svg') {
                    $mimeType = 'image/svg+xml';
                }
            }
            
            $base64 = base64_encode($imageData);
            return "data:{$mimeType};base64,{$base64}";
        }
        return null;
    }
}