<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CompressResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        if (! $this->shouldCompress($request, $response)) {
            return $response;
        }

        $content = $response->getContent();
        if ($content === false || $content === '') {
            return $response;
        }

        $encoding = $this->preferredEncoding($request);
        if ($encoding === 'br' && function_exists('brotli_compress')) {
            $compressed = brotli_compress($content, 4);
            if ($compressed !== false) {
                $response->setContent($compressed);
                $response->headers->set('Content-Encoding', 'br');
                $response->headers->remove('Content-Length');

                return $response;
            }
        }

        if (function_exists('gzencode')) {
            $compressed = gzencode($content, 6);
            if ($compressed !== false) {
                $response->setContent($compressed);
                $response->headers->set('Content-Encoding', 'gzip');
                $response->headers->remove('Content-Length');
            }
        }

        return $response;
    }

    private function shouldCompress(Request $request, Response $response): bool
    {
        if ($response->getStatusCode() !== 200) {
            return false;
        }

        if ($response->headers->has('Content-Encoding')) {
            return false;
        }

        $type = (string) $response->headers->get('Content-Type', '');
        if (! str_contains($type, 'json') && ! str_contains($type, 'text') && ! str_contains($type, 'javascript')) {
            return false;
        }

        $length = strlen((string) $response->getContent());

        return $length >= 1024;
    }

    private function preferredEncoding(Request $request): string
    {
        $accept = strtolower((string) $request->header('Accept-Encoding', ''));

        if (str_contains($accept, 'br')) {
            return 'br';
        }

        if (str_contains($accept, 'gzip')) {
            return 'gzip';
        }

        return '';
    }
}
