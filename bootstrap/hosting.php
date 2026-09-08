<?php

use Illuminate\Support\Env;

/**
 * Shared-hosting bootstrap (InfinityFree and similar).
 * Must run after Composer autoload and before Laravel boots.
 */
if (! function_exists('putenv')) {
    Env::disablePutenv();
}

if (! empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
}
