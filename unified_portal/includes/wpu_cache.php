<?php
/**
 * Lightweight cache layer for legacy WPU portal (file + optional Redis).
 * Compatible with Laravel CACHE_STORE=redis when phpredis is available.
 */

if (! function_exists('wpu_cache_dir')) {
    function wpu_cache_dir(): string
    {
        static $dir = null;
        if ($dir !== null) {
            return $dir;
        }
        $base = dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'framework'.DIRECTORY_SEPARATOR.'wpu_cache';
        if (! is_dir($base)) {
            @mkdir($base, 0755, true);
        }
        $dir = $base;

        return $dir;
    }
}

if (! function_exists('wpu_cache_key_path')) {
    function wpu_cache_key_path(string $key): string
    {
        return wpu_cache_dir().DIRECTORY_SEPARATOR.sha1($key).'.cache';
    }
}

if (! function_exists('wpu_cache_redis')) {
    function wpu_cache_redis(): ?Redis
    {
        static $redis = null;
        static $attempted = false;

        if ($attempted) {
            return $redis;
        }
        $attempted = true;

        $store = getenv('CACHE_STORE') ?: 'file';
        if ($store !== 'redis' || ! class_exists('Redis')) {
            return null;
        }

        try {
            $host = getenv('REDIS_HOST') ?: '127.0.0.1';
            $port = (int) (getenv('REDIS_PORT') ?: 6379);
            $password = getenv('REDIS_PASSWORD');
            $prefix = getenv('CACHE_PREFIX') ?: 'wpu_his_';

            $client = new Redis;
            if (! $client->connect($host, $port, 1.0)) {
                return null;
            }
            if ($password !== false && $password !== '' && $password !== 'null') {
                $client->auth($password);
            }
            $client->setOption(Redis::OPT_PREFIX, $prefix);
            $redis = $client;
        } catch (Throwable) {
            $redis = null;
        }

        return $redis;
    }
}

if (! function_exists('wpu_cache_get')) {
    function wpu_cache_get(string $key, mixed $default = null): mixed
    {
        $redis = wpu_cache_redis();
        if ($redis instanceof Redis) {
            $raw = $redis->get('wpu:'.$key);
            if ($raw === false) {
                return $default;
            }

            return unserialize($raw);
        }

        $path = wpu_cache_key_path($key);
        if (! is_readable($path)) {
            return $default;
        }

        $payload = @file_get_contents($path);
        if ($payload === false) {
            return $default;
        }

        $data = @unserialize($payload);
        if (! is_array($data) || ! isset($data['expires'], $data['value'])) {
            return $default;
        }

        if ($data['expires'] !== 0 && $data['expires'] < time()) {
            @unlink($path);

            return $default;
        }

        return $data['value'];
    }
}

if (! function_exists('wpu_cache_put')) {
    function wpu_cache_put(string $key, mixed $value, int $ttlSeconds = 300): bool
    {
        $redis = wpu_cache_redis();
        if ($redis instanceof Redis) {
            return (bool) $redis->setex('wpu:'.$key, max(1, $ttlSeconds), serialize($value));
        }

        $path = wpu_cache_key_path($key);
        $payload = serialize([
            'expires' => $ttlSeconds > 0 ? time() + $ttlSeconds : 0,
            'value' => $value,
        ]);

        return file_put_contents($path, $payload, LOCK_EX) !== false;
    }
}

if (! function_exists('wpu_cache_forget')) {
    function wpu_cache_forget(string $key): void
    {
        $redis = wpu_cache_redis();
        if ($redis instanceof Redis) {
            $redis->del('wpu:'.$key);

            return;
        }

        $path = wpu_cache_key_path($key);
        if (is_file($path)) {
            @unlink($path);
        }
    }
}

if (! function_exists('wpu_cache_remember')) {
    function wpu_cache_remember(string $key, int $ttlSeconds, callable $callback): mixed
    {
        $cached = wpu_cache_get($key);
        if ($cached !== null) {
            return $cached;
        }

        $value = $callback();
        wpu_cache_put($key, $value, $ttlSeconds);

        return $value;
    }
}

if (! function_exists('wpu_cache_flush_reference')) {
    function wpu_cache_flush_reference(): void
    {
        foreach ([
            'ref:patient_types',
            'ref:departments',
            'ref:case_types',
            'ref:certificate_codes',
            'settings:auto_lock',
            'dashboard:stats',
        ] as $key) {
            wpu_cache_forget($key);
        }
    }
}
