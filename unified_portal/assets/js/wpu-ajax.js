/**
 * WPU HIS — AJAX helpers: deduplication, debounce, abort, retry.
 * Wraps window.fetch without changing existing call sites.
 */
(function (global) {
  'use strict';

  var inflight = new Map();
  var debounceTimers = new Map();
  var nativeFetch = global.fetch ? global.fetch.bind(global) : null;

  function requestKey(input, init) {
    var url = typeof input === 'string' ? input : (input && input.url) || '';
    var method = String((init && init.method) || 'GET').toUpperCase();
    var body = init && init.body ? String(init.body) : '';
    return method + ' ' + url + ' ' + body;
  }

  function cloneResponse(response) {
    try {
      if (response && typeof response.clone === 'function') {
        return response.clone();
      }
    } catch (e) {
      // Body already consumed; caller will see the original error.
    }
    return response;
  }

  function shareResponse(promise) {
    return promise.then(cloneResponse);
  }

  function wpuFetch(input, init) {
    init = init || {};
    var key = requestKey(input, init);
    var dedupe = init.wpuDedupe !== false;

    if (dedupe && inflight.has(key)) {
      return shareResponse(inflight.get(key));
    }

    if (!nativeFetch) {
      throw new Error('fetch is not available');
    }
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

      var promise = nativeFetch(input, merged)
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

    return shareResponse(run());
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

  if (!global.__wpuFetchPatched && nativeFetch) {
    global.__wpuFetchPatched = true;
    global.fetch = function (input, init) {
      if (init && init.wpuRaw === true) {
        var copy = Object.assign({}, init);
        delete copy.wpuRaw;
        return nativeFetch(input, copy);
      }
      return wpuFetch(input, init);
    };
  }
})(window);
