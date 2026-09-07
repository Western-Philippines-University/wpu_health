#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
DIST="$ROOT/netlify-dist"
PORT=8765

mkdir -p "$DIST"

php "$ROOT/artisan" serve --host=127.0.0.1 --port="$PORT" &
SERVER_PID=$!
sleep 3

cleanup() {
  kill "$SERVER_PID" 2>/dev/null || true
}
trap cleanup EXIT

curl -fsS "http://127.0.0.1:${PORT}/" \
  | sed 's|http://localhost/wpu_medical-master/admin/login|/admin/login|g' \
  | sed "s|http://127.0.0.1:${PORT}/admin/login|/admin/login|g" \
  > "$DIST/index.html"

if [[ -n "${ADMIN_BACKEND_URL:-}" ]]; then
  cat > "$DIST/_redirects" <<EOF
/admin/*  ${ADMIN_BACKEND_URL}/admin/:splat  200!
/portal/*  ${ADMIN_BACKEND_URL}/portal/:splat  200!
/up  ${ADMIN_BACKEND_URL}/up  200!
EOF
  rm -f "$DIST/admin/index.html"
  echo "Admin routes will proxy to ${ADMIN_BACKEND_URL}"
else
  rm -f "$DIST/_redirects"
  echo "ADMIN_BACKEND_URL not set — /admin shows placeholder until backend is configured."
fi

echo "Exported landing page to netlify-dist/index.html"
