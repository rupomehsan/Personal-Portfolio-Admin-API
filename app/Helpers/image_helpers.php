<?php

if (!function_exists('asset_or_default')) {
    /**
     * Return an asset URL for the given image path or a default placeholder.
     * - If $path is empty/null return the default URL
     * - If $path starts with http return it as-is
     * - If the file exists in public path return asset($path)
     * - Otherwise return default
     *
     * @param string|null $path
     * @param string|null $default
     * @return string
     */
    function assetHelper($path = null, $default = null)
    {
        // allow passing the default as null -> set a sensible placeholder
        $default = $default ?: '/dummy.png';

        if (!$path) {
            return asset($default);
        }

        // If it's already a full URL
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        // Normalize leading slash
        $publicPath = public_path(ltrim($path, '/'));

        if (file_exists($publicPath) && is_file($publicPath)) {
            return asset($path);
        }

        // Fallback to default
        return asset($default);
    }
}
