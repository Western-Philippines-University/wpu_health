<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PublicPortalController extends Controller
{
    public function index(): \Illuminate\Http\Response
    {
        $path = base_path('unified_portal/index.php');
        if (! is_readable($path)) {
            abort(404);
        }

        $cwd = getcwd();
        chdir(dirname($path));
        ob_start();
        try {
            include $path;
        } finally {
            if ($cwd !== false) {
                chdir($cwd);
            }
        }

        return response(ob_get_clean());
    }

    public function asset(string $path): BinaryFileResponse
    {
        $path = ltrim(str_replace('\\', '/', $path), '/');
        $base = realpath(base_path('unified_portal/assets'));
        if ($base === false) {
            abort(404);
        }

        $full = realpath($base.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $path));
        if ($full === false || ! str_starts_with($full, $base)) {
            abort(404);
        }

        $response = new BinaryFileResponse($full);
        $ext = strtolower(pathinfo($full, PATHINFO_EXTENSION));
        $mime = match ($ext) {
            'css' => 'text/css; charset=UTF-8',
            'js' => 'application/javascript; charset=UTF-8',
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'webp' => 'image/webp',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'ico' => 'image/x-icon',
            default => null,
        };
        if ($mime !== null) {
            $response->headers->set('Content-Type', $mime);
        }

        return $response;
    }
}
