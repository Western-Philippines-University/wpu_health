/**
 * WPU HIS — AJAX helpers: deduplication, debounce, abort, retry.
 * Wraps window.fetch without changing existing call sites.
 */
(function (global) {
  'use strict';

  var inflight = new Map();
  var debounceTimers = new Map();

  function requestKey(input, init) {
    var url = typeof input === 'string' ? input : (input && input.url) || '';
    var method = String((init && init.method) || 'GET').toUpperCase();
    var body = init && init.body ? String(init.body) : '';
    return method + ' ' + url + ' ' + body;
  }

  function wpuFetch(input, init) {
    init = init || {};
    var key = requestKey(input, init);
    var dedupe = init.wpuDedupe !== false;

    if (dedupe && inflight.has(key)) {
      return inflight.get(key);
    }

    var baseFetch = global.fetch.bind(global);
    var attempt = 0;
    var maxRetries = typeof init.wpuRetries === 'number' ? init.wpuRetries : 0;
    var timeoutMs = typeof init.wpuTimeout === 'number' ? init.wpuTimeout : 30000;

    function run() {
      var controller = new AbortController();
      var timer = setTimeout(function () {
        controller.abort();
      }, timeoutMs);

      var merged = Object.assign({}, init, { signal: controller.signal });
      delete merged.wpuDedupe;
      delete merged.wpuRetries;
      delete merged.wpuTimeout;

      var promise = baseFetch(input, merged)
        .then(function (response) {
          clearTimeout(timer);
          if (!response.ok && attempt < maxRetries) {
            attempt++;
            return run();
          }
          return response;
        })
        .catch(function (err) {
          clearTimeout(timer);
          if (attempt < maxRetries) {
            attempt++;
            return run();
          }
          throw err;
        })
        .finally(function () {
          inflight.delete(key);
        });

      if (dedupe) {
        inflight.set(key, promise);
      }

      return promise;
    }

    return run();
  }

  function debounce(key, fn, wait) {
    if (debounceTimers.has(key)) {
      clearTimeout(debounceTimers.get(key));
    }
    return new Promise(function (resolve, reject) {
      debounceTimers.set(key, setTimeout(function () {
        debounceTimers.delete(key);
        Promise.resolve(fn()).then(resolve).catch(reject);
      }, wait || 300));
    });
  }

  function abortAll() {
    inflight.clear();
  }

  global.WpuAjax = {
    fetch: wpuFetch,
    debounce: debounce,
    abortAll: abortAll,
  };

  if (!global.__wpuFetchPatched && global.fetch) {
    global.__wpuFetchPatched = true;
    var originalFetch = global.fetch;
    global.fetch = function (input, init) {
      if (init && init.wpuRaw === true) {
        var copy = Object.assign({}, init);
        delete copy.wpuRaw;
        return originalFetch(input, copy);
      }
      return wpuFetch(input, init);
    };
  }
})(window);
