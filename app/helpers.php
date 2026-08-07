<?php

if (!function_exists('asset_v')) {
    /**
     * Cache-busted asset URL. Appends the file's real modification time as a
     * ?v= query string so browsers always fetch a fresh copy after any edit,
     * without anyone having to remember to bump a manual version number
     * (the recurring cause of "why isn't my CSS/JS change showing up" bugs
     * in this project).
     */
    function asset_v(string $path): string
    {
        $absolute = public_path(ltrim($path, '/'));
        $version = is_file($absolute) ? filemtime($absolute) : time();

        return asset($path) . '?v=' . $version;
    }
}
